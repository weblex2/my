<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         ⚠️  GALLERY RESET - ALLES LÖSCHEN  ⚠️          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

echo "Folgende Tabellen werden geleert:\n";
echo "  • gallery_texts         (Foto-Texte)\n";
echo "  • gallery_pic_content   (Thumbnails & Bilder)\n";
echo "  • gallery_pics          (Foto-Metadaten)\n";
echo "  • gallery_mappoint      (Reise-Orte)\n";
echo "  • gallery               (Galerien)\n\n";

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
    echo "  $table: $count\n";
}

$total = array_sum($counts);
echo "\nGesamt: $total Einträge\n\n";

if ($total == 0) {
    echo "✅ Die Tabellen sind bereits leer!\n";
    exit;
}

// Bestätigung abfragen
echo "⚠️  ACHTUNG: Diese Aktion kann NICHT rückgängig gemacht werden!\n";
echo "   Alle Galerien, Orte, Fotos und Texte werden gelöscht.\n\n";

$confirm = readline("Bist du sicher? (ja/nein): ");

if (strtolower(trim($confirm)) !== 'ja') {
    echo "❌ Abbruch. Es wurde nichts gelöscht.\n";
    exit;
}

echo "\n🗑️  Lösche Einträge...\n\n";

DB::beginTransaction();
try {
    // In richtiger Reihenfolge löschen (wegen Foreign Keys)

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

    echo "\n╔══════════════════════════════════════════════════════════╗\n";
    echo "║              ✅ RESET ERFOLGREICH!                       ║\n";
    echo "╚══════════════════════════════════════════════════════════╝\n\n";

    echo "Alle Gallery-Tabellen wurden geleert.\n";
    echo "Auto-Increment IDs wurden zurückgesetzt.\n\n";
    echo "Du kannst jetzt mit frischen Daten starten! 🚀\n\n";

    // Auto-Increment zurücksetzen
    echo "Setze Auto-Increment IDs zurück...\n";
    DB::statement('ALTER TABLE gallery AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_mappoint AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_pics AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_pic_content AUTO_INCREMENT = 1');
    DB::statement('ALTER TABLE gallery_texts AUTO_INCREMENT = 1');
    echo "✅ IDs zurückgesetzt!\n\n";

} catch (\Exception $e) {
    DB::rollback();
    echo "\n❌ FEHLER: " . $e->getMessage() . "\n";
    echo "Es wurde KEIN Datensatz gelöscht (Rollback).\n";
    exit(1);
}
