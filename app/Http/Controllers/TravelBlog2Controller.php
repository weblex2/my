<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryMappoint;
use App\Models\GalleryPics;
use App\Models\GalleryPicContent;
use App\Models\GalleryText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * Travel-Blog 2 — eigenstaendige Ansicht mit Weltkarte, eingefaerbten Laendern
 * und durchlaufendem Foto-Feed.
 *
 * Beruehrt den bestehenden GalleryController nicht. Fuer den Upload wird
 * bewusst dieselbe BlogCreator/TripAutoAssigner-Kette benutzt wie in
 * GalleryController::storeBulk(), damit die EXIF- und Zuordnungslogik
 * an genau einer Stelle gepflegt wird.
 */
class TravelBlog2Controller extends Controller
{
    /** Fotos pro Feed-Seite */
    private const PER_PAGE = 6;

    public function index()
    {
        return view('travelblog2.index');
    }

    public function upload()
    {
        return view('travelblog2.upload', [
            'galleries' => Gallery::orderBy('name')->get(['id', 'name', 'code']),
        ]);
    }

    /**
     * Upload einer einzelnen Datei. Gleiche Kette wie storeBulk().
     */
    public function store(Request $request)
    {
        try {
            $bc = new \App\MyClasses\BlogCreator($request);
            $bc->uploadFile();
            $bc->loadMedia();
            $bc->createThumbNails();
            $bc->createBlog();
        } catch (\Exception $e) {
            \Log::error('travelblog2 store failed', [
                'file'  => $request->file?->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return Response::json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }

        $result = ['status' => 'ok'];

        if (!empty($bc->assignResult)) {
            $result['gallery_id']    = $bc->assignResult['gallery_id']    ?? null;
            $result['gallery_name']  = $bc->assignResult['gallery_name']  ?? null;
            $result['mappoint_name'] = $bc->assignResult['mappoint_name'] ?? null;
            $result['country']       = $bc->assignResult['geo']['country']      ?? null;
            $result['country_code']  = $bc->assignResult['geo']['country_code'] ?? null;
        }

        return Response::json($result);
    }

    /**
     * Freigabe-Ansicht: zeigt alle noch nicht freigegebenen Fotos.
     */
    public function admin()
    {
        $pending = GalleryPics::where('approved', 0)
            ->orderByRaw('COALESCE(taken_at, created_at) ASC')
            ->get();

        $galleries = Gallery::whereIn('id', $pending->pluck('gallery_id')->unique())
            ->pluck('name', 'id');

        return view('travelblog2.admin', [
            'photos'    => $this->mapPhotos($pending),
            'raw'       => $pending->keyBy('id'),
            'galleries' => $galleries,
            'total'     => $pending->count(),
        ]);
    }

    /**
     * Freigeben oder verwerfen. Verwerfen loescht nur die Zuordnung nicht —
     * das Foto bleibt in der DB und kann spaeter noch freigegeben werden.
     */
    public function approve(Request $request)
    {
        $ids = array_filter(array_map('intval', (array) $request->input('ids', [])));

        if (empty($ids)) {
            return back()->with('tb2_msg', 'Nichts ausgewählt.');
        }

        if ($request->input('action') === 'delete') {
            GalleryPics::whereIn('id', $ids)->delete();
            GalleryPicContent::whereIn('pic_id', $ids)->delete();
            GalleryText::whereIn('pic_id', $ids)->delete();
            $msg = count($ids) . ' Foto(s) gelöscht.';
        } else {
            GalleryPics::whereIn('id', $ids)->update(['approved' => 1]);
            $msg = count($ids) . ' Foto(s) freigegeben.';
        }

        return back()->with('tb2_msg', $msg);
    }

    /**
     * Alle bereisten Laender inkl. Kartenpunkte.
     * Grundlage fuer Einfaerbung und Pins.
     */
    public function countries()
    {
        $trips = $this->orderedTrips();

        // Nur Orte zeigen, an denen auch ein freigegebenes Foto haengt — sonst
        // verraten neue Pins Orte, bevor die Fotos freigegeben sind.
        $visibleMappointIds = GalleryPics::where('approved', 1)
            ->whereNotNull('mappoint_id')
            ->distinct()
            ->pluck('mappoint_id');

        $mappoints = GalleryMappoint::whereIn('gallery_id', $trips->pluck('id'))
            ->whereIn('id', $visibleMappointIds)
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->get(['id', 'gallery_id', 'mappoint_name', 'lat', 'lon', 'ord'])
            ->groupBy('gallery_id');

        $out = $trips->map(function ($t) use ($mappoints) {
            return [
                'gallery_id' => $t['id'],
                'name'       => $t['name'],
                'code'       => strtoupper((string) $t['code']),
                'pic_count'  => $t['pic_count'],
                'started_at' => $t['started_at'],
                'points'     => collect($mappoints->get($t['id'], []))->map(fn($m) => [
                    'id'   => $m->id,
                    'name' => $m->mappoint_name,
                    'lat'  => (float) $m->lat,
                    'lon'  => (float) $m->lon,
                ])->values(),
            ];
        });

        return Response::json([
            'countries' => $out,
            'totals'    => [
                'countries' => $out->count(),
                'photos'    => $out->sum('pic_count'),
                'points'    => $out->sum(fn($c) => count($c['points'])),
            ],
        ]);
    }

    /**
     * Foto-Feed. Laeuft innerhalb einer Reise und danach automatisch in die
     * naechste weiter — der Client bekommt dafuer next_trip mitgeliefert und
     * setzt eine Ueberschrift davor.
     */
    public function feed(Request $request)
    {
        $trips = $this->orderedTrips();

        if ($trips->isEmpty()) {
            return Response::json(['trip' => null, 'photos' => [], 'next_trip' => null]);
        }

        $galleryId = (int) $request->get('gallery', 0);
        $offset    = max(0, (int) $request->get('offset', 0));

        $pos = $trips->search(fn($t) => $t['id'] === $galleryId);
        if ($pos === false) {
            $pos       = 0;
            $offset    = 0;
            $galleryId = $trips[0]['id'];
        }

        $trip = $trips[$pos];

        $pics = GalleryPics::where('gallery_id', $galleryId)
            ->where('approved', 1)
            ->orderByRaw('COALESCE(taken_at, created_at) ASC')
            ->orderBy('ord')
            ->offset($offset)
            ->limit(self::PER_PAGE)
            ->get();

        $photos     = $this->mapPhotos($pics);
        $loadedTo   = $offset + $pics->count();
        $hasMore    = $loadedTo < $trip['pic_count'];

        // Naechste Reise nur melden, wenn diese hier durch ist
        $nextTrip = null;
        if (!$hasMore && isset($trips[$pos + 1])) {
            $n = $trips[$pos + 1];
            $nextTrip = [
                'gallery_id' => $n['id'],
                'name'       => $n['name'],
                'code'       => strtoupper((string) $n['code']),
                'pic_count'  => $n['pic_count'],
            ];
        }

        return Response::json([
            'trip' => [
                'gallery_id' => $trip['id'],
                'name'       => $trip['name'],
                'code'       => strtoupper((string) $trip['code']),
                'pic_count'  => $trip['pic_count'],
            ],
            'photos'     => $photos,
            'offset'     => $loadedTo,
            'has_more'   => $hasMore,
            'next_trip'  => $nextTrip,
            'is_last'    => !$hasMore && $nextTrip === null,
        ]);
    }

    /* ------------------------------------------------------------------ */

    /**
     * Reisen mit mindestens einem Foto, sortiert nach fruehestem Aufnahme-
     * datum. Reisen ohne Datum haengen hinten dran — in den Daten hat aktuell
     * nur ein Teil der Fotos taken_at gesetzt.
     */
    private function orderedTrips()
    {
        $counts = GalleryPics::where('approved', 1)
            ->selectRaw('gallery_id, COUNT(*) as c, MIN(COALESCE(taken_at, created_at)) as started')
            ->groupBy('gallery_id')
            ->get()
            ->keyBy('gallery_id');

        return Gallery::whereIn('id', $counts->keys())
            ->get(['id', 'name', 'code'])
            ->map(function ($g) use ($counts) {
                $row = $counts->get($g->id);
                return [
                    'id'         => $g->id,
                    'name'       => $g->name,
                    'code'       => $g->code,
                    'pic_count'  => (int) $row->c,
                    'started_at' => $row->started,
                ];
            })
            // Ein zusammengesetzter Schluessel statt mehrerer Closures: Reisen mit
            // Aufnahmedatum zuerst chronologisch, danach die undatierten nach Namen.
            ->sortBy(fn($t) => (empty($t['started_at']) ? '1' : '0')
                . str_pad((string) $t['started_at'], 19)
                . $t['name'])
            ->values();
    }

    /**
     * Fotos in die Feed-Struktur bringen. Bildgroessen und Texte werden
     * gebuendelt geladen, damit pro Seite nur wenige Queries anfallen.
     */
    private function mapPhotos($pics)
    {
        if ($pics->isEmpty()) {
            return collect();
        }

        $picIds = $pics->pluck('id');
        $videos = ['mov', 'mp4'];

        $allSizes = GalleryPicContent::whereIn('pic_id', $picIds)
            ->select('pic_id', 'size')
            ->get()
            ->groupBy('pic_id')
            ->map(fn($rows) => $rows->pluck('size')->toArray());

        $allTexts = GalleryText::whereIn('pic_id', $picIds)
            ->where('language', session('lang', 'DE'))
            ->get()
            ->keyBy('pic_id');

        return $pics->map(function ($pic) use ($videos, $allSizes, $allTexts) {
            $ext     = strtolower(pathinfo($pic->pic, PATHINFO_EXTENSION));
            $isVideo = in_array($ext, $videos);
            $sizes   = $allSizes->get($pic->id, []);

            // In den Bestandsdaten gibt es nur die Groesse "1" - die benannten
            // Groessen TN/M/L/XL existieren dort nicht. Deshalb immer auf die
            // erste vorhandene Groesse zurueckfallen, statt leer auszugehen.
            $pick = function (array $prefs) use ($sizes) {
                foreach ($prefs as $s) {
                    if (in_array($s, $sizes, true)) return $s;
                }
                return $sizes[0] ?? null;
            };

            $movie = null;

            if ($isVideo) {
                $thumb = $pick(['TN', 'M', 'L', 'XL']);
                $movie = in_array('MOV', $sizes, true) ? 'MOV' : null;
            } else {
                $thumb = $pick(['TN', 'M', 'L', 'XL']);
            }

            // Grosse Fassung fuer die Lightbox; faellt auf das Thumbnail
            // zurueck, wenn es (wie beim Altbestand) nur eine Groesse gibt.
            $large = $pick(['XL', 'L', 'M', 'TN']);

            $text = $allTexts->get($pic->id);

            return [
                'id'       => $pic->id,
                'is_video' => $isVideo,
                'src'      => $thumb ? route('gallery.serveImage', [$pic->id, $thumb]) : null,
                'full'     => $large ? route('gallery.serveImage', [$pic->id, $large]) : null,
                'video'    => $movie ? route('gallery.serveImage', [$pic->id, $movie]) : null,
                'lat'      => $pic->lat !== null ? (float) $pic->lat : null,
                'lon'      => $pic->lon !== null ? (float) $pic->lon : null,
                'taken_at' => $pic->taken_at ? \Carbon\Carbon::parse($pic->taken_at)->format('d.m.Y H:i') : null,
                'text'     => $text ? $text->text : null,
            ];
        })->values();
    }
}
