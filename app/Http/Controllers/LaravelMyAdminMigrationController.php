<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\App;

class LaravelMyAdminMigrationController extends Controller
{
    function execMigration($migrationPath){
        $migrator = App::make(Migrator::class);
        // Setze den Pfad zur Migration
        $migrationPath = database_path('migrations');
        // Starte die Migration
        $migrator->run([$migrationPath]);
        return response()->json(['message' => 'Migration erfolgreich ausgeführt']);

    }

    function rollbackMigration($migrationPath){
        $migrator = App::make(Migrator::class);
        // Setze den Pfad zur Migration
        $migrationPath = database_path('migrations');
        $migrator->rollback([$migrationPath]);
    }

}
