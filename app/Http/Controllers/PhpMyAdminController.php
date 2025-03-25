<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PhpMyAdminController extends Controller
{
    // Punkt 1: Tabellenübersicht
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        //dd($tables);
        return view('laravelMyAdmin.index', compact('tables'));
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
                $result['signed'] = false;
            } else {
                $result['signed'] = true;
            }
        }
        // if nothing found
        if ($result['type']=='') {
            $result['type'] = $columnDefinition;
        }

        return $result;
    }

    function getDatabases(){
        $res = DB::select('SELECT * from information_schema.columns where table_schema !="information_schema" order by table_schema, TABLE_NAME, column_name');

        foreach ($res as $field){
            //dd($field);
            $db     = $field->TABLE_SCHEMA;
            $table  = $field->TABLE_NAME;
            $column  = $field->COLUMN_NAME;
            $dbs[$db][$table][$column] = $field;
        }
        dd($dbs);
        return $dbs;
    }

    function createTable(Request $request){
        return response()->json(['status'=>200,'data'=>'Jupp Table creation']);
    }
}
