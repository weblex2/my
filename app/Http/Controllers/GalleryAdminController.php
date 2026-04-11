<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\GalleryPics;
use App\Models\GalleryMappoint;
use App\Models\GalleryText;
use App\Models\GalleryPicContent;
use Illuminate\Support\Facades\DB;

class GalleryAdminController extends Controller
{
    /**
     * Admin Dashboard - Übersicht aller Galerien
     */
    public function index()
    {
        $galleries = Gallery::withCount(['GalleryPics', 'GalleryMappoint'])
            ->with(['GalleryMappoint' => function($query) {
                $query->withCount('GalleryPics');
            }])
            ->orderBy('name')
            ->get();

        $stats = [
            'total_galleries'   => Gallery::count(),
            'total_mappoints'   => GalleryMappoint::count(),
            'total_pics'        => GalleryPics::count(),
            'total_text_entries' => GalleryText::count(),
        ];

        return view('gallery.admin.index', compact('galleries', 'stats'));
    }

    // ═══════════════════════════════════════════════
    // GALLERY CRUD
    // ═══════════════════════════════════════════════

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:gallery,code',
            'country_map_name' => 'nullable|string|max:255',
        ]);

        $gallery = Gallery::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Galerie erfolgreich erstellt',
            'gallery' => $gallery,
        ]);
    }

    public function updateGallery(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:gallery,code,' . $id,
            'country_map_name' => 'nullable|string|max:255',
        ]);

        $gallery->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Galerie erfolgreich aktualisiert',
            'gallery' => $gallery,
        ]);
    }

    public function deleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Galerie und alle zugehörigen Daten wurden gelöscht',
        ]);
    }

    // ═══════════════════════════════════════════════
    // Mappoint CRUD
    // ═══════════════════════════════════════════════

    public function getMappoints($galleryId)
    {
        $mappoints = GalleryMappoint::where('gallery_id', $galleryId)
            ->withCount('GalleryPics')
            ->with(['GalleryPics' => function($query) {
                $query->with(['Thumbnail', 'GalleryText'])->limit(3);
            }])
            ->orderBy('ord')
            ->get();

        return response()->json(['mappoints' => $mappoints]);
    }

    public function storeMappoint(Request $request)
    {
        $validated = $request->validate([
            'gallery_id'      => 'required|exists:gallery,id',
            'mappoint_name'   => 'required|string|max:255',
            'lat'             => 'required|numeric',
            'lon'             => 'required|numeric',
            'description'     => 'nullable|string',
        ]);

        $maxOrd = GalleryMappoint::where('gallery_id', $validated['gallery_id'])->max('ord') ?? 0;
        $validated['ord'] = $maxOrd + 1;

        $mappoint = GalleryMappoint::create($validated);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Ort erfolgreich hinzugefügt',
            'mappoint' => $mappoint,
        ]);
    }

    public function updateMappoint(Request $request, $id)
    {
        $mappoint = GalleryMappoint::findOrFail($id);

        $validated = $request->validate([
            'mappoint_name'   => 'required|string|max:255',
            'lat'             => 'required|numeric',
            'lon'             => 'required|numeric',
            'description'     => 'nullable|string',
            'ord'             => 'nullable|integer',
        ]);

        $mappoint->update($validated);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Ort erfolgreich aktualisiert',
            'mappoint' => $mappoint,
        ]);
    }

    public function deleteMappoint($id)
    {
        DB::beginTransaction();
        try {
            $mappoint = GalleryMappoint::findOrFail($id);
            
            // Get all pics for this mappoint
            $pics = GalleryPics::where('mappoint_id', $id)->get();
            
            foreach ($pics as $pic) {
                // Delete pic texts
                GalleryText::where('pic_id', $pic->id)->delete();
                // Delete pic content
                GalleryPicContent::where('pic_id', $pic->id)->delete();
                // Delete pic
                $pic->delete();
            }
            
            // Delete mappoint
            $mappoint->delete();
            
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Ort und alle zugehörigen Fotos wurden gelöscht',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Fehler beim Löschen: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reorderMappoints(Request $request)
    {
        $validated = $request->validate([
            'mappoint_ids' => 'required|array',
        ]);

        foreach ($validated['mappoint_ids'] as $index => $id) {
            GalleryMappoint::where('id', $id)->update(['ord' => $index + 1]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Reihenfolge aktualisiert',
        ]);
    }

    // ═══════════════════════════════════════════════
    // PHOTO MANAGEMENT
    // ═══════════════════════════════════════════════

    public function getPhotos($mappointId = null, Request $request)
    {
        $query = GalleryPics::with(['Thumbnail', 'GalleryText', 'Mappoint', 'Gallery']);

        if ($mappointId) {
            $query->where('mappoint_id', $mappointId);
        }

        if ($request->has('gallery_id')) {
            $query->where('gallery_id', $request->gallery_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('GalleryText', function($q) use ($search) {
                $q->where('text', 'like', "%{$search}%");
            });
        }

        $photos = $query->orderByRaw('COALESCE(taken_at, created_at) DESC')
            ->paginate(20);

        return response()->json(['photos' => $photos]);
    }

    public function deletePhoto($id)
    {
        DB::beginTransaction();
        try {
            $photo = GalleryPics::findOrFail($id);
            
            // Delete related data
            GalleryText::where('pic_id', $id)->delete();
            GalleryPicContent::where('pic_id', $id)->delete();
            $photo->delete();
            
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Foto erfolgreich gelöscht',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Fehler beim Löschen: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function bulkDeletePhotos(Request $request)
    {
        $validated = $request->validate([
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:gallery_pics,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['photo_ids'] as $photoId) {
                GalleryText::where('pic_id', $photoId)->delete();
                GalleryPicContent::where('pic_id', $photoId)->delete();
                GalleryPics::where('id', $photoId)->delete();
            }
            
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => count($validated['photo_ids']) . ' Foto(s) gelöscht',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Fehler beim Löschen: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function assignPhotos(Request $request)
    {
        $validated = $request->validate([
            'photo_ids'     => 'required|array',
            'photo_ids.*'   => 'exists:gallery_pics,id',
            'gallery_id'    => 'required|exists:gallery,id',
            'mappoint_id'   => 'required|exists:gallery_mappoint,id',
        ]);

        $maxOrd = GalleryPics::where('mappoint_id', $validated['mappoint_id'])->max('ord') ?? 0;

        foreach ($validated['photo_ids'] as $index => $photoId) {
            GalleryPics::where('id', $photoId)->update([
                'gallery_id'   => $validated['gallery_id'],
                'mappoint_id'  => $validated['mappoint_id'],
                'ord'          => $maxOrd + $index + 1,
            ]);

            GalleryText::where('pic_id', $photoId)->update([
                'gallery_id'   => $validated['gallery_id'],
                'mappoint_id'  => $validated['mappoint_id'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => count($validated['photo_ids']) . ' Foto(s) erfolgreich zugewiesen',
        ]);
    }

    public function updatePhotoText(Request $request, $id)
    {
        $validated = $request->validate([
            'text'     => 'nullable|string',
            'language' => 'required|string|size:2',
        ]);

        $text = GalleryText::updateOrCreate(
            [
                'pic_id'   => $id,
                'language' => $validated['language'],
            ],
            ['text' => $validated['text']]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Text erfolgreich aktualisiert',
            'text'   => $text,
        ]);
    }

    public function updatePhotoDate(Request $request, $id)
    {
        $validated = $request->validate([
            'taken_at' => 'nullable|date',
        ]);

        $photo = GalleryPics::findOrFail($id);
        $photo->update(['taken_at' => $validated['taken_at']]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Datum erfolgreich aktualisiert',
            'photo'   => $photo,
        ]);
    }

    public function reorderPhotos(Request $request)
    {
        $validated = $request->validate([
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:gallery_pics,id',
        ]);

        foreach ($validated['photo_ids'] as $index => $id) {
            GalleryPics::where('id', $id)->update(['ord' => $index + 1]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Foto-Reihenfolge aktualisiert',
        ]);
    }

    // ═══════════════════════════════════════════════
    // SEARCH & FILTER
    // ═══════════════════════════════════════════════

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $results = [
            'galleries'  => Gallery::where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->limit(10)
                ->get(),
            
            'mappoints'  => GalleryMappoint::where('mappoint_name', 'like', "%{$query}%")
                ->with('gallery')
                ->limit(10)
                ->get(),
            
            'photos'     => GalleryPics::whereHas('GalleryText', function($q) use ($query) {
                    $q->where('text', 'like', "%{$query}%");
                })
                ->with(['Thumbnail', 'Mappoint', 'GalleryText'])
                ->limit(10)
                ->get(),
        ];

        return response()->json($results);
    }
}
