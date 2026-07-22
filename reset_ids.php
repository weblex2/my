<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

DB::statement('ALTER TABLE gallery AUTO_INCREMENT = 1');
DB::statement('ALTER TABLE gallery_mappoint AUTO_INCREMENT = 1');
DB::statement('ALTER TABLE gallery_pics AUTO_INCREMENT = 1');
DB::statement('ALTER TABLE gallery_pic_content AUTO_INCREMENT = 1');
DB::statement('ALTER TABLE gallery_texts AUTO_INCREMENT = 1');

echo "✅ Auto-Increment IDs zurückgesetzt!\n";
