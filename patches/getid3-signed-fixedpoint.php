<?php
/**
 * Patcht getID3: 16.16-Fixpoint-Werte vorzeichenbehaftet lesen.
 *
 * getid3_lib::FixedPoint16_16() liest den Ganzzahl-Anteil mit BigEndian2Int(),
 * also vorzeichenlos. Im QuickTime-Format ist 16.16 aber signiert. Negative
 * Werte kommen dadurch um exakt 65536 zu gross an:
 *
 *     iPhone-Video, loci-Atom:  lat -14.093689  ->  65521.906311
 *
 * Betroffen ist vor allem GPS aus MOV/MP4 (Feed ordnet das Video sonst der
 * falschen Reise zu) und die Rotationsmatrix im tkhd-Atom. getID3 vergleicht
 * dort gegen 65535 statt -1 - die Faelle muessen deshalb mitgezogen werden,
 * sonst wird nach dem Fix keine Rotation mehr erkannt. getID3 kennt das
 * Problem selbst, siehe Kommentar "2017-Dec-28" in module.audio-video.quicktime.php.
 *
 * Laeuft ueber composer.json -> post-autoload-dump, also bei jedem
 * composer install/update. Idempotent: bereits gepatchte Dateien bleiben
 * unangetastet, fehlende Dateien werden still uebersprungen.
 */

$root   = dirname(__DIR__);
$vendor = $root . '/vendor/james-heinrich/getid3/getid3';

$changed = [];
$skipped = [];

// ── 1. FixedPoint16_16: Ganzzahl-Anteil als Zweierkomplement lesen ──────────
$libFile = $vendor . '/getid3.lib.php';

if (is_file($libFile)) {
    $src = file_get_contents($libFile);

    if (strpos($src, 'SIGNED-FIXEDPOINT-PATCH') !== false) {
        $skipped[] = 'getid3.lib.php (bereits gepatcht)';
    } else {
        $pattern = '/(public static function FixedPoint16_16\(\$rawdata\)\s*\{\s*)'
                 . 'return self::BigEndian2Int\(substr\(\$rawdata, 0, 2\)\)/';

        $replacement = '$1' . implode("\n\t\t", [
            '// SIGNED-FIXEDPOINT-PATCH: 16.16 ist vorzeichenbehaftet, siehe patches/',
            '$integer = self::BigEndian2Int(substr($rawdata, 0, 2));',
            'if ($integer >= 0x8000) {',
            "\t" . '$integer -= 0x10000;',
            '}',
            'return $integer',
        ]);

        $patched = preg_replace($pattern, $replacement, $src, 1, $count);

        if ($count === 1 && $patched !== null) {
            file_put_contents($libFile, $patched);
            $changed[] = 'getid3.lib.php: FixedPoint16_16 vorzeichenbehaftet';
        } else {
            $skipped[] = 'getid3.lib.php: Muster nicht gefunden - getID3-Version geaendert?';
        }
    }
}

// ── 2. Rotationsmatrix: 65535 war der vorzeichenlose Artefakt von -1 ────────
$qtFile = $vendor . '/module.audio-video.quicktime.php';

if (is_file($qtFile)) {
    $src = file_get_contents($qtFile);

    $cases = [
        "case '0:1:65535:0':"     => "case '0:1:-1:0':",
        "case '65535:0:0:65535':" => "case '-1:0:0:-1':",
        "case '0:65535:1:0':"     => "case '0:-1:1:0':",
    ];

    $hits = 0;
    foreach ($cases as $old => $new) {
        if (strpos($src, $old) !== false) {
            $src = str_replace($old, $new, $src);
            $hits++;
        }
    }

    if ($hits > 0) {
        file_put_contents($qtFile, $src);
        $changed[] = "module.audio-video.quicktime.php: {$hits} Rotationsfaelle auf -1";
    } else {
        $skipped[] = 'module.audio-video.quicktime.php (bereits gepatcht oder Version geaendert)';
    }
}

foreach ($changed as $c) {
    echo "  [getID3-Patch] ", $c, PHP_EOL;
}
foreach ($skipped as $s) {
    echo "  [getID3-Patch] uebersprungen: ", $s, PHP_EOL;
}

// Nie fehlschlagen - ein nicht anwendbarer Patch darf kein Deploy blockieren.
// ExifExtractor::normalizeQuicktimeCoord faengt den Fall ohnehin ab.
exit(0);
