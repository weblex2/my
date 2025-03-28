<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LaravelMyAdminMigrationController;

class LaravelMyAdminController extends Controller
{
    // Punkt 1: Tabellenübersicht
    public function index()
    {
        Session::put('db', '');
        $tables = DB::select('SHOW TABLES');
        return view('laravelMyAdmin.index', compact('tables'));
    }

    public function dbindex($db=null){
        Session::put('db', $db);
        $tables = $this->getTablesFromDb($db);
        return view('laravelMyAdmin.database', compact('tables'));
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
        $table = $request->input('table');
        $changes = $request->input('changes');  // Beispiel: ['add' => ['column_name' => 'type']]

        $migrationName = 'add_columns_to_' . $table . '_' . time();
        $migrationPath = database_path('migrations/' . date('Y_m_d_His') . '_'. $migrationName . '.php');

        $content = $this->generateMigrationContent($table, $changes);

        File::put($migrationPath, $content);

        return redirect()->route('laravelMyAdmin.index')->with('success', 'Migration wurde erfolgreich generiert.');
    }

    // Hilfsmethode, um Migration zu erstellen
    private function generateMigrationContent($table, $changes)
    {
        $migrationContent = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\nuse Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\n";
        $migrationContent .= "class " . ucfirst($changes['class']) . " extends Migration\n{\n";
        $migrationContent .= "    public function up()\n    {\n";
        $migrationContent .= "        Schema::table('$table', function (Blueprint \$table) {\n";

        foreach ($changes as $action => $columns) {
            foreach ($columns as $column => $type) {
                if ($action == 'add') {
                    $migrationContent .= "            \$table->$type('$column');\n";
                }
            }
        }

        $migrationContent .= "        });\n";
        $migrationContent .= "    }\n\n";
        $migrationContent .= "    public function down()\n    {\n";
        $migrationContent .= "        Schema::table('$table', function (Blueprint \$table) {\n";
        foreach ($changes as $action => $columns) {
            foreach ($columns as $column => $type) {
                if ($action == 'add') {
                    $migrationContent .= "            \$table->dropColumn('$column');\n";
                }
            }
        }
        $migrationContent .= "        });\n";
        $migrationContent .= "    }\n";
        $migrationContent .= "}\n";

        return $migrationContent;
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
                $result['signed'] = $matches[3];
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

     private function getFieldsFromTable($db, $table){
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

    function showTable($db, $table){
        Session::put('db', $db);
        Session::put('table', $table);
        $fields = $this->getFieldsFromTable($db, $table);
        return view('laravelMyAdmin.table', compact('fields'));
    }

    function testMigration(){
        $this->setDatabase('laravel2');
        #$migration = '2025_03_27_215940_create_tests_table.php';
        $migration = '2025_03_28_193709_create_tests2_table.php';
        $migration = 'database/migrations/'.$migration;
        $output['down'] = $this->migrationUp($migration);
        $output['up']   = $this->migrationUp($migration);
        return view('laravelMyAdmin.execMigration', compact('output'));
        /* return response()->json([
            //'message' => 'Migration erfolgreich ausgeführt',
            'output' => $output
        ]);
 */
    }

    private function migrationDown($migration){
        // Migration down
        Artisan::call('migrate:rollback', ['--path' => $migration ]);
        $output = Artisan::output(); // Holt die Ausgabe des Artisan-Befehls
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

    function execMigration(Request $request){
        $req = $request->all();
        return response()->json([
            'data' => '123'
        ]);

    }
}
