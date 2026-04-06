<?php

namespace App\MyClasses;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Owenoj\LaravelGetId3\GetId3;

/**
 * Robuste EXIF-Extraktion für iPhone (JPEG/HEIC) und Android.
 *
 * Strategie GPS:
 *   1. GetId3 computed lat/lon (fertig berechnete Dezimalwerte)
 *   2. GetId3 raw Brüche → eigene Berechnung
 *   3. exif_read_data() raw Brüche → eigene Berechnung  (Fallback)
 *
 * Strategie Timestamp:
 *   1. EXIF.DateTimeOriginal  (Aufnahmezeitpunkt, immer bevorzugen)
 *   2. GPS.GPSDateStamp + GPS.GPSTimeStamp
 *   3. IFD0.DateTime          (NICHT IFD0: das ist oft der Import-/Bearbeitungszeitpunkt!)
 */
class ExifExtractor
{
    private string $filePath;
    private string $ext;

    /** @var array|null Raw GetId3 meta */
    private ?array $id3 = null;

    /** @var array|null Raw exif_read_data result */
    private ?array $native = null;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->ext      = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Public API
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Gibt ['lat', 'lon', 'taken_at', 'source', 'make', 'model'] zurück.
     * Fehlende Werte sind null.
     */
    public function extract(): array
    {
        $this->loadId3();
        $this->loadNative();

        $lat     = $this->extractLat();
        $lon     = $this->extractLon();
        $takenAt = $this->extractTimestamp();
        $make    = $this->extractMake();
        $model   = $this->extractModel();

        return [
            'lat'      => $lat,
            'lon'      => $lon,
            'taken_at' => $takenAt,
            'make'     => $make,
            'model'    => $model,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Loaders
    // ─────────────────────────────────────────────────────────────────────

    private function loadId3(): void
    {
        try {
            $track      = new GetId3($this->filePath);
            $this->id3  = $track->extractInfo();
        } catch (\Exception $e) {
            Log::debug('ExifExtractor: GetId3 failed for ' . basename($this->filePath) . ' – ' . $e->getMessage());
            $this->id3 = [];
        }
    }

    private function loadNative(): void
    {
        // exif_read_data only works on JPEG/TIFF
        if (!in_array($this->ext, ['jpg', 'jpeg', 'tiff', 'tif'])) {
            $this->native = [];
            return;
        }
        try {
            $this->native = @exif_read_data($this->filePath, 0, true) ?: [];
        } catch (\Exception $e) {
            $this->native = [];
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    //  GPS extraction
    // ─────────────────────────────────────────────────────────────────────

    private function extractLat(): ?float
    {
        // 1. GetId3 computed (preferred — already decimal)
        $computed = $this->id3[$this->ext]['exif']['GPS']['computed']['latitude']
                 ?? $this->id3['jpg']['exif']['GPS']['computed']['latitude']
                 ?? null;
        if ($computed !== null) {
            $val = (float) $computed;
            // Apply S hemisphere if needed
            $ref = $this->id3[$this->ext]['exif']['GPS']['GPSLatitudeRef']
                ?? $this->id3['jpg']['exif']['GPS']['GPSLatitudeRef']
                ?? 'N';
            return ($ref === 'S') ? -abs($val) : abs($val);
        }

        // 2. GetId3 raw fractions
        $raw = $this->id3[$this->ext]['exif']['GPS']['GPSLatitude']
            ?? $this->id3['jpg']['exif']['GPS']['GPSLatitude']
            ?? null;
        $ref = $this->id3[$this->ext]['exif']['GPS']['GPSLatitudeRef']
            ?? $this->id3['jpg']['exif']['GPS']['GPSLatitudeRef']
            ?? 'N';
        if ($raw) {
            return $this->gpsToDecimal($raw, $ref);
        }

        // 3. exif_read_data fallback
        $raw = $this->native['GPS']['GPSLatitude'] ?? null;
        $ref = $this->native['GPS']['GPSLatitudeRef'] ?? 'N';
        if ($raw) {
            return $this->gpsToDecimal($raw, $ref);
        }

        return null;
    }

    private function extractLon(): ?float
    {
        // 1. GetId3 computed
        $computed = $this->id3[$this->ext]['exif']['GPS']['computed']['longitude']
                 ?? $this->id3['jpg']['exif']['GPS']['computed']['longitude']
                 ?? null;
        if ($computed !== null) {
            $val = (float) $computed;
            $ref = $this->id3[$this->ext]['exif']['GPS']['GPSLongitudeRef']
                ?? $this->id3['jpg']['exif']['GPS']['GPSLongitudeRef']
                ?? 'E';
            return ($ref === 'W') ? -abs($val) : abs($val);
        }

        // 2. GetId3 raw fractions
        $raw = $this->id3[$this->ext]['exif']['GPS']['GPSLongitude']
            ?? $this->id3['jpg']['exif']['GPS']['GPSLongitude']
            ?? null;
        $ref = $this->id3[$this->ext]['exif']['GPS']['GPSLongitudeRef']
            ?? $this->id3['jpg']['exif']['GPS']['GPSLongitudeRef']
            ?? 'E';
        if ($raw) {
            return $this->gpsToDecimal($raw, $ref);
        }

        // 3. exif_read_data fallback
        $raw = $this->native['GPS']['GPSLongitude'] ?? null;
        $ref = $this->native['GPS']['GPSLongitudeRef'] ?? 'E';
        if ($raw) {
            return $this->gpsToDecimal($raw, $ref);
        }

        return null;
    }

    /**
     * Wandelt GPS-Koordinate (Brüche ODER Dezimalzahl) in Dezimalgrad um.
     *
     * Unterstützte Formate:
     *  - Array mit Brüchen:  ["48/1", "2/1", "3514/100"]   (iPhone, Android)
     *  - Array mit Floats:   [48.0, 2.0, 35.14]            (manche Android)
     *  - Bereits Dezimal:    48.043                         (seltene Tools)
     */
    private function gpsToDecimal(mixed $coordinate, string $hemisphere): ?float
    {
        if (!is_array($coordinate)) {
            // Bereits ein Dezimalwert
            $val = (float) $coordinate;
            return ($hemisphere === 'S' || $hemisphere === 'W') ? -abs($val) : abs($val);
        }

        if (count($coordinate) < 3) return null;

        $parts = [];
        foreach ($coordinate as $item) {
            if (is_string($item) && str_contains($item, '/')) {
                [$num, $den] = explode('/', $item);
                $den = (float) $den;
                $parts[] = $den != 0 ? (float) $num / $den : 0.0;
            } else {
                $parts[] = (float) $item;
            }
        }

        [$deg, $min, $sec] = $parts;
        $decimal = $deg + ($min / 60.0) + ($sec / 3600.0);
        $sign    = ($hemisphere === 'S' || $hemisphere === 'W') ? -1 : 1;

        return $sign * $decimal;
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Timestamp extraction
    // ─────────────────────────────────────────────────────────────────────

    private function extractTimestamp(): ?Carbon
    {
        // 1. EXIF DateTimeOriginal — IMMER bevorzugen (echter Aufnahmezeitpunkt)
        //    IFD0.DateTime ist oft der Import-/Bearbeitungszeitpunkt (z.B. Windows Photo Viewer)
        $candidates = [
            $this->id3[$this->ext]['exif']['EXIF']['DateTimeOriginal'] ?? null,
            $this->id3['jpg']['exif']['EXIF']['DateTimeOriginal']       ?? null,
            $this->native['EXIF']['DateTimeOriginal']                   ?? null,
        ];

        foreach ($candidates as $str) {
            $dt = $this->parseExifDate($str);
            if ($dt) return $dt;
        }

        // 2. GPS DateStamp + TimeStamp (iPhone backup, UTC-basiert)
        $dateStamp = $this->id3[$this->ext]['exif']['GPS']['GPSDateStamp']
                  ?? $this->id3['jpg']['exif']['GPS']['GPSDateStamp']
                  ?? $this->native['GPS']['GPSDateStamp']
                  ?? null;
        $timeStamp = $this->id3[$this->ext]['exif']['GPS']['GPSTimeStamp']
                  ?? $this->id3['jpg']['exif']['GPS']['GPSTimeStamp']
                  ?? $this->native['GPS']['GPSTimeStamp']
                  ?? null;

        if ($dateStamp && $timeStamp) {
            $dt = $this->parseGpsDateTime($dateStamp, $timeStamp);
            if ($dt) return $dt;
        }

        // 3. IFD0 DateTime — letzter Ausweg, kann Importzeit sein
        $fallbacks = [
            $this->id3[$this->ext]['exif']['IFD0']['DateTime'] ?? null,
            $this->id3['jpg']['exif']['IFD0']['DateTime']       ?? null,
            $this->native['IFD0']['DateTime']                   ?? null,
        ];

        foreach ($fallbacks as $str) {
            $dt = $this->parseExifDate($str);
            if ($dt) return $dt;
        }

        return null;
    }

    private function parseExifDate(?string $str): ?Carbon
    {
        if (!$str || strlen($str) < 10) return null;
        // EXIF format: "2020:05:29 18:01:26"
        try {
            return Carbon::createFromFormat('Y:m:d H:i:s', $str);
        } catch (\Exception) {}
        // Try standard formats as fallback
        try {
            return Carbon::parse($str);
        } catch (\Exception) {}
        return null;
    }

    private function parseGpsDateTime(string $dateStamp, mixed $timeStamp): ?Carbon
    {
        // dateStamp: "2020:05:29"
        // timeStamp: ["20/1", "0/1", "0/1"] or [20, 0, 0]
        try {
            $date = str_replace(':', '-', $dateStamp);
            if (is_array($timeStamp)) {
                $parts = array_map(function($v) {
                    if (is_string($v) && str_contains($v, '/')) {
                        [$n, $d] = explode('/', $v);
                        return (int) ($d != 0 ? $n / $d : 0);
                    }
                    return (int) $v;
                }, $timeStamp);
                $time = sprintf('%02d:%02d:%02d', $parts[0], $parts[1] ?? 0, $parts[2] ?? 0);
            } else {
                $time = '00:00:00';
            }
            return Carbon::createFromFormat('Y-m-d H:i:s', "$date $time", 'UTC');
        } catch (\Exception) {
            return null;
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Camera make / model
    // ─────────────────────────────────────────────────────────────────────

    private function extractMake(): ?string
    {
        return $this->id3[$this->ext]['exif']['IFD0']['Make']
            ?? $this->id3['jpg']['exif']['IFD0']['Make']
            ?? $this->native['IFD0']['Make']
            ?? null;
    }

    private function extractModel(): ?string
    {
        return $this->id3[$this->ext]['exif']['IFD0']['Model']
            ?? $this->id3['jpg']['exif']['IFD0']['Model']
            ?? $this->native['IFD0']['Model']
            ?? null;
    }
}
