<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Gallery Config Einträge ===\n\n";
$configs = DB::table('gallery_config')->get();
echo "Total: " . $configs->count() . " Einträge\n\n";

foreach ($configs as $c) {
    echo "ID: {$c->id} | option: {$c->option} | value: {$c->value} | value2: {$c->value2}\n";
}

echo "\n\n=== Test: LIKE Query mit pic_size% ===\n";
$test1 = DB::table('gallery_config')->where('option', 'like', 'pic_size%')->get();
echo "Ergebnis: " . $test1->count() . " Einträge\n";
foreach ($test1 as $c) {
    echo "  - {$c->option}\n";
}

echo "\n=== Test: LIKE Query mit pic_size_% ===\n";
$test2 = DB::table('gallery_config')->where('option', 'like', 'pic_size_%')->get();
echo "Ergebnis: " . $test2->count() . " Einträge\n";
foreach ($test2 as $c) {
    echo "  - {$c->option}\n";
}
