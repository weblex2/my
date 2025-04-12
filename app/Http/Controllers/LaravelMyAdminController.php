<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LaravelMyAdminMigrationController;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\LaravelColumnCreator;

class LaravelMyAdminController extends Controller
{
    // Punkt 1: Tabellenübersicht
    public function index()
    {
        Session::forget('db', '');
        Session::forget('table');
        $tables = DB::select('SHOW TABLES');
        return view('laravelMyAdmin.index', compact('tables'));
    }

    public function dbindex($db=null){
        Session::put('db', $db);
        Session::forget('table');
        $tables = $this->getTablesFromDb($db);
        return view('laravelMyAdmin.database', compact('db', 'tables'));
    }

    // Punkt 2: Bearbeiten einer Tabelle
    public function edit($table, $db = 'laravel')
    {
        $columns = DB::select("SELECT * FROM information_schema.columns WHERE table_schema = '".$db."' AND  table_name = '".$table."'");

        foreach ($columns as $i => $column){
            $columns[$i]->Datatype = $this->parseColumnDefinition($column->COLUMN_TYPE);
        }
        //dd($columns);
        return view('laravelMyAdmin.edit', compact('table', 'columns'));
    }


    public function generateMigration(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $action = $data['data']['action'];
        $table = $data['data']['tableName'];
        $rows = $data['data']['rows'];

        #$migrationName = 'add_columns_to_' . $table . '_' . time();
        $migrationName = 'create_' . $table . '_' . time();
        $migrationPath = database_path('migrations/' . date('Y_m_d_His') . '_'. $migrationName . '.php');

        $content = $this->generateMigrationContent($action, $table, $rows);

        File::put($migrationPath, $content);


    }

    // Hilfsmethode, um Migration zu erstellen
    private function generateMigrationContent($action, $table, $fields)
    {
        if ($action=="create-table"){
            $filePath = base_path('app/Templates/LaravelMyadmin/create_table.txt');
            $fileContent = file_get_contents($filePath);  // Liest die Datei
            $fileContent = str_replace('{{table}}', $table, $fileContent);
            $fieldDefinition = "";
            foreach ($fields as $field){
                $columnName = str_replace(["{","}"],"", $field['name']);
                $datatype = str_replace(["{","}"],"", $field['datatype']);
                switch ($datatype) {
                    case "id" :
                        $fieldDefinition .= '$table->id();'."\n";
                        break;
                    case "string" :
                        $fieldDefinition .= '$table->string("'.$columnName.'");'."\n";
                        break;
                    case "text" :
                        $fieldDefinition .= '$table->text("'.$columnName.'");'."\n";
                        break;
                     case "decimal" :
                        $fieldDefinition .= '$table->decimal("'.$columnName.'");'."\n";
                        break;
                    case "timestamps":
                        $fieldDefinition .= '$table->timestamps("'.$columnName.'");'."\n";
                        break;
                }
            }
            $fileContent = str_replace('{{fields}}', $fieldDefinition, $fileContent);
        }

        return $fileContent;
    }

    function parseColumnDefinition($columnDefinition) {
        // Initialisiere das Ergebnis-Array
        $result = [
            'type' => '',
            'length' => null,
            'signed' => false
        ];

        // Entferne Leerzeichen und teile die Definition
        $columnDefinition = trim($columnDefinition);

        // Finde den Typ (z.B. bigint)
        if (preg_match('/^(\w+)\((\d+)\)( unsigned)?$/i', $columnDefinition, $matches)) {
            $result['type'] = strtolower($matches[1]); // z.B. 'bigint'
            $result['length'] = (int) $matches[2]; // z.B. 20

            // Überprüfen, ob 'unsigned' vorhanden ist
            if (!empty($matches[3])) {
                $result['signed'] = trim($matches[3]);
            }
            else{
                $result['signed'] = "";
            }
        }
        // if nothing found
        if ($result['type']=='') {
            $result['type'] = $columnDefinition;
        }
        return $result;
    }

    function getDatabases(){
        $res = DB::select('SELECT * from information_schema.columns order by table_schema, TABLE_NAME, column_name');

        foreach ($res as $field){
            $db     = $field->TABLE_SCHEMA;
            $table  = $field->TABLE_NAME;
            $column  = $field->COLUMN_NAME;
            $dbs[$db][$table][$column] = $field;
        }
        return $dbs;
    }

    private function getTablesFromDb($db){
        $qr="SELECT *
             FROM information_schema.tables
             WHERE table_schema ='".$db."'
             ORDER BY TABLE_NAME";
        $res = DB::select($qr);
        return $res;
    }

    public function getFieldsFromTable($db, $table){
        $qr="SELECT *
             FROM information_schema.columns
             WHERE table_schema ='".$db."'
             AND table_name ='".$table."'
             ORDER BY ordinal_position";
        $columns = DB::select($qr);
        $columns = $this->getParsedColumns($columns);
        return $columns;
    }

    private function getParsedColumns($columns){
        foreach ($columns as $i => $column){
            $columns[$i]->Datatype = $this->parseColumnDefinition($column->COLUMN_TYPE);
        }
        return $columns;
    }

    public function createTable(Request $request){
        return response()->json(['status'=>200,'data'=>'Jupp Table creation']);
    }

    public function modifyTable(Request $request){
        $table = session('table');
        $req = $request->all();
        $columns = json_decode($req['rows'],1);
        $migrationColumnsText = LaravelColumnCreator::createModifiedFields($columns);
        return response()->json(['status'=>200,'data'=>'Jupp Table creation']);
    }



    public function newTable($db){
        return view('laravelMyAdmin.newTable');
    }

    function showTableStructure($db, $table){
        Session::put('db', $db);
        Session::put('table', $table);
        $fields = $this->getFieldsFromTable($db, $table);
        return view('laravelMyAdmin.showTableStructure', compact('fields'));
    }

     function editTableStructure(){
        $db     = session('db');
        $table  = session('table');
        $fields = $this->getFieldsFromTable($db, $table);
        return view('laravelMyAdmin.editTableStructure', compact('fields'));
    }

    /* function testMigration(){
        $this->setDatabase('laravel2');
        #$migration = '2025_03_27_215940_create_tests_table.php';
        $migration = '2025_03_28_193709_create_tests2_table.php';
        $migration = 'database/migrations/'.$migration;
        $output['down'] = $this->migrationDown($migration);
        $output['up']   = $this->migrationUp($migration);
        return view('laravelMyAdmin.execMigration', compact('output'));

    } */

    private function migrationDown($migration){
        // Migration down
        Artisan::call('migrate:rollback', ['--path' => $migration ]);
        $output = Artisan::output(); // Holt die Ausgabe des Artisan-Befehls
        return $output;
    }

    private function migrationUp($migration){
        // Migration up
        Artisan::call('migrate', ['--path' => $migration]);
        $output = Artisan::output(); // Holt die Ausgabe des Artisan-Befehls
        return $output;
    }

    private function setDatabase($database){
        Config::set('database.connections.dynamic', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $database,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);
        // Verbindung zurücksetzen und auf neue Datenbank wechseln
        DB::purge('dynamic');
        DB::reconnect('dynamic');
        // Standardverbindung auf die neue setzen
        Config::set('database.default', 'dynamic');
    }

    function testMigration(Request $request){
        $req = $request->all();
        $migrations = $req['migrations'];
        $this->setDatabase(session('db'));
        foreach($migrations as $migration){
            $migration = 'database/migrations/'.$migration;
            $output['down'] = $this->migrationDown($migration);
            $output['up']   = $this->migrationUp($migration);
        }
        return response()->json(['data' => $output], 200);
    }

    function formatMigrationOutput($output){
        return $output;
    }

    function execMigration(Request $request){
        $req = $request->all();
        $migration = $req['migration'];
        $action = $req['action'];
        $this->setDatabase(session('db'));
        $migration = 'database/migrations/'.$migration;
        if ($action=="up"){
            $output = $this->migrationUp($migration);
        }
        else{
            $output = $this->migrationDown($migration);
        }
        return response()->json(['data' => $output], 200);
    }

    public function addRowsToTable(){
        $html = view('components.laravel-my-admin.new-table-row', [
            'name' => 'New Field',
            'type' => 'string'
        ])->render();
        return response()->json(['data' => $html], 200);
    }

    public function tools(){
        $output = "";
        return view('laravelMyAdmin.tools', compact('output'));
    }

    public function showMigrations(){
        $migrationPath = database_path('migrations');
        $migrations  = array_diff(scandir($migrationPath), array('..', '.'));
        rsort($migrations);
        $db_migrations =  DB::table('migrations')->pluck('migration')->toArray();
        $new_migrations = [] ;
        foreach($migrations as $migration){
            $migration = trim( $migration, '.php');
            if (!in_array($migration, $db_migrations)){
                $new_migrations[] = $migration.".php";
            }
        }
        $changedMigrations = count($new_migrations);
        return view('laravelMyAdmin.migrations', compact('migrations','new_migrations','changedMigrations'));
    }

    public static function getNewMigrationCount(){
        $migrationPath = database_path('migrations');
        $migrations  = array_diff(scandir($migrationPath), array('..', '.'));
        $db_migrations =  DB::table('migrations')->pluck('migration')->toArray();
        $new_migrations = [] ;
        foreach($migrations as $migration){
            $migration = trim( $migration, '.php');
            if (!in_array($migration, $db_migrations)){
                $new_migrations[] = $migration.".php";
            }
        }
        return count($new_migrations);
    }

    public function showTableContent($db, $table, $page=1){

        $this->setDatabase($db);
        Session::put('db', $db);
        Session::put('table', $table);
        $qr = "SELECT * FROM ".$table."";
        $results = DB::select($qr); // Direkte DB-Abfrage

        $currentPage = $page; // Aktuelle Seite
        $perPage = 10; // Anzahl der Elemente pro Seite

        // Slice die Ergebnisse für die aktuelle Seite
        $itemsForCurrentPage = array_slice($results, ($currentPage - 1) * $perPage, $perPage);

        // Paginator erstellen
        $paginator = new LengthAwarePaginator($itemsForCurrentPage, count($results), $perPage, $currentPage);

        return view('laravelMyAdmin.showTableContent', ['content' => $paginator]);
        //return view('laravelMyAdmin.showTableContent', compact('content'));
    }

    // Table Operations
    function truncateTable(Request $request){
        $table = $request->input('table');
        return $table;
    }

    function clearCache(){
        return view('laravelMyAdmin.clearCache');
    }

}
