<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         GALLERY RESET - ALLES LÖSCHEN                   ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// Anzahl der Einträge zählen
$counts = [
    'gallery_texts'       => DB::table('gallery_texts')->count(),
    'gallery_pic_content' => DB::table('gallery_pic_content')->count(),
    'gallery_pics'        => DB::table('gallery_pics')->count(),
    'gallery_mappoint'    => DB::table('gallery_mappoint')->count(),
    'gallery'             => DB::table('gallery')->count(),
];

echo "Aktuelle Einträge:\n";
foreach ($counts as $table => $count) {
    if ($count > 0) {
        echo "  • $table: $count\n";
    }
}

$total = array_sum($counts);

if ($total == 0) {
    echo "\n✅ Die Tabellen sind bereits leer!\n";
    exit;
}

echo "\n⚠️  Lösche $total Einträge...\n\n";

DB::beginTransaction();
try {
    echo "  1/5 gallery_texts... ";
    DB::table('gallery_texts')->truncate();
    echo "✅\n";
    
    echo "  2/5 gallery_pic_content... ";
    DB::table('gallery_pic_content')->truncate();
    echo "✅\n";
    
    echo "  3/5 gallery_pics... ";
    DB::table('gallery_pics')->truncate();
    echo "✅\n";
    
    echo "  4/5 gallery_mappoint... ";
    DB::table('gallery_mappoint')->truncate();
    echo "✅\n";
    
    echo "  5/5 gallery... ";
    DB::table('gallery')->truncate();
    echo "✅\n";
    
    DB::commit();
    
    echo "\n✅ Reset erfolgreich!\n";
    echo "Auto-Increment IDs zurücksetzen...\n";
    
    DB::statement('ALTER TABLE gallery AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_mappoint AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_pics AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_pic_content AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_texts AUTO_INCREMENT = 1');
    
    echo "✅ Fertig! Alle Gallery-Daten wurden gelöscht.\n\n";
    
} catch (\Exception $e) {
    DB::rollback();
    echo "\n❌ FEHLER: " . $e->getMessage() . "\n";
    exit(1);
}
