<?php

namespace App\MyClasses;

use App\Models\Gallery;
use App\Models\GalleryMappoint;
use App\Models\GalleryPics;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripAutoAssigner
{
    const MAX_TRIP_DAYS    = 42;   // 6 Wochen
    const MAX_MAPPOINT_KM  = 50;   // gleicher Ort bis 50km
    const NOMINATIM_URL    = 'https://nominatim.openstreetmap.org/reverse';

    private array $geoInfo = [];

    /**
     * Gibt gallery_id, mappoint_id und geo-Infos zurück.
     * Legt Gallery / Mappoint automatisch an falls nötig.
     *
     * @throws \RuntimeException wenn GPS oder Timestamp fehlen
     */
    public function assign(float $lat, float $lon, Carbon $takenAt): array
    {
        $this->geoInfo = $this->reverseGeocode($lat, $lon);

        $gallery  = $this->findOrCreateGallery($takenAt);
        $mappoint = $this->findOrCreateMappoint($gallery, $lat, $lon, $takenAt);

        return [
            'gallery_id'    => $gallery->id,
            'gallery_name'  => $gallery->name,
            'mappoint_id'   => $mappoint->id,
            'mappoint_name' => $mappoint->mappoint_name,
            'geo'           => $this->geoInfo,
        ];
    }

    // ─────────────────────────────────────────────────────────
    //  Reverse Geocoding via OpenStreetMap Nominatim (kostenlos)
    // ─────────────────────────────────────────────────────────
    private function reverseGeocode(float $lat, float $lon): array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'noppal.de/travelblog (contact@noppal.de)',
            ])->timeout(5)->get(self::NOMINATIM_URL, [
                'lat'            => $lat,
                'lon'            => $lon,
                'format'         => 'json',
                'addressdetails' => 1,
            ]);

            $data = $response->json();
            $addr = $data['address'] ?? [];

            return [
                'country'      => $addr['country']      ?? 'Unknown',
                'country_code' => strtoupper($addr['country_code'] ?? 'XX'),
                'city'         => $addr['city']    ?? $addr['town']    ?? $addr['village']
                               ?? $addr['municipality'] ?? $addr['county'] ?? null,
                'state'        => $addr['state']   ?? null,
            ];
        } catch (\Exception $e) {
            Log::warning('TripAutoAssigner: Nominatim failed – ' . $e->getMessage());
            return [
                'country'      => 'Unknown',
                'country_code' => 'XX',
                'city'         => null,
                'state'        => null,
            ];
        }
    }

    // ─────────────────────────────────────────────────────────
    //  Passende Gallery finden oder neu anlegen
    // ─────────────────────────────────────────────────────────
    private function findOrCreateGallery(Carbon $takenAt): Gallery
    {
        $code = $this->geoInfo['country_code'];

        // Alle Galleries für dieses Land holen
        $galleries = Gallery::where('code', $code)->get();

        foreach ($galleries as $gallery) {
            $stats = GalleryPics::where('gallery_id', $gallery->id)
                ->whereNotNull('taken_at')
                ->selectRaw('MIN(taken_at) as min_date, MAX(taken_at) as max_date')
                ->first();

            if (!$stats || !$stats->min_date) {
                // Gallery ohne Fotos → nehmen falls keine bessere vorhanden
                continue;
            }

            $tripStart = Carbon::parse($stats->min_date);
            $tripEnd   = Carbon::parse($stats->max_date);

            // Das Foto passt wenn:
            // - es NACH oder AM Anfang der Reise liegt UND
            // - der Trip nach Einschluss noch max. 42 Tage dauert
            $daysFromStart = $tripStart->diffInDays($takenAt, false); // negativ = vor Reisebeginn
            $newTripEnd    = max($tripEnd->timestamp, $takenAt->timestamp);
            $newDuration   = $tripStart->diffInDays(Carbon::createFromTimestamp($newTripEnd));

            if ($daysFromStart >= -1 && $newDuration <= self::MAX_TRIP_DAYS) {
                return $gallery;
            }
        }

        // Keine passende Gallery → neue anlegen
        return $this->createGallery($takenAt);
    }

    private function createGallery(Carbon $takenAt): Gallery
    {
        $code    = $this->geoInfo['country_code'];
        $country = $this->geoInfo['country'];

        // Zähle wie viele Reisen ins gleiche Land schon existieren
        $existing = Gallery::where('code', $code)->count();
        $year     = $takenAt->format('Y');

        $name = $country . ' ' . $year;
        if ($existing > 0) {
            $name .= ' #' . ($existing + 1);
        }

        $gallery = new Gallery();
        $gallery->name             = $name;
        $gallery->code             = $code;
        $gallery->country_map_name = strtolower($country);
        $gallery->save();

        return $gallery;
    }

    // ─────────────────────────────────────────────────────────
    //  Passenden Mappoint finden oder neu anlegen
    // ─────────────────────────────────────────────────────────
    private function findOrCreateMappoint(Gallery $gallery, float $lat, float $lon, Carbon $takenAt): GalleryMappoint
    {
        $mappoints = GalleryMappoint::where('gallery_id', $gallery->id)->get();

        $nearest     = null;
        $nearestDist = PHP_FLOAT_MAX;

        foreach ($mappoints as $mp) {
            if (!$mp->lat || !$mp->lon) continue;
            $dist = $this->haversine($lat, $lon, (float)$mp->lat, (float)$mp->lon);
            if ($dist < $nearestDist) {
                $nearestDist = $dist;
                $nearest = $mp;
            }
        }

        if ($nearest && $nearestDist <= self::MAX_MAPPOINT_KM) {
            return $nearest;
        }

        // Neuen Mappoint anlegen
        $cityName = $this->geoInfo['city']
            ?? $this->geoInfo['state']
            ?? ($this->geoInfo['country'] . ' ' . $takenAt->format('d.m.Y'));

        $mp = new GalleryMappoint();
        $mp->gallery_id    = $gallery->id;
        $mp->country_id    = $gallery->code;
        $mp->lat           = $lat;
        $mp->lon           = $lon;
        $mp->mappoint_name = $cityName;
        $mp->ord           = $mappoints->count() + 1;
        $mp->save();

        return $mp;
    }

    // ─────────────────────────────────────────────────────────
    //  Haversine-Distanz in km
    // ─────────────────────────────────────────────────────────
    public function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R    = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat / 2) ** 2
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
