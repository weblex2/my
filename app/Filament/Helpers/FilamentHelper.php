<?php

namespace App\Filament\Helpers;

use Filament\Tables\Columns\Column;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;
use App\Exports\CustomExport;
use App\Models\GeneralSetting;
use App\Models\FilTableFields;
use Illuminate\Support\Facades\Artisan;



class FilamentHelper
{
    private $data;

    public static function readSetting($field){
        $setting = GeneralSetting::where('field', $field)->first();
        // Wenn der Datensatz existiert, gibt den Wert zurück, sonst null
        return $setting ? $setting->value : false;
    }

    public static function renderPdf($id){
        $quote = Quote::with(['customer', 'quoteProducts.product'])->find($id);
        $quote->quoteProducts = $quote->quoteProducts->sortBy('reoccurance');
        $pdf['content'] = Pdf::loadView('filament.pdf.quote', ['quote' => $quote]);
        $pdf['quote_number'] = $quote->quote_number;
        return $pdf;
    }

    public static function generateFile($id)
    {
        $quote = Quote::with(['customer', 'quoteProducts.product'])->find($id);

        if (!$quote) {
            throw new \Exception("Quote mit ID $id nicht gefunden.");
        }

        $quote->quoteProducts = $quote->quoteProducts->sortBy('reoccurance');

        $pdf = Pdf::loadView('filament.pdf.quote', ['quote' => $quote]);

        $filename = 'Angebot_' . $quote->quote_number . '.pdf';
        $path = storage_path('app/public/quotes/' . $filename);

        // Verzeichnis sicherstellen
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        // PDF speichern
        $pdf->save($path);
        return $path;
    }

    public static function excelExport($data){
        $exportData = [];
        $header = array_keys($data[0]->getAttributes());
        $exportData[] = $header;
        foreach ($data as $item) {
            $row = [];
            foreach ($item->getAttributes() as $key => $value) {
                $row[ucwords(str_replace('_', ' ', $key))] = $value;
            }
            $exportData[] = $row;
        }
        return new CustomExport($exportData);
    }

    public static function createField(array $field){
        $filename = self::createMigrationFile($field);
        $result = self::execMigration($filename);
        // if migration was not successful delete it
        if ($result['status']=='Fail'){
            unlink($filename);
        }
        else{

            switch($field['type']){
                case 'string':
                    $type = "text";
                    break;
                case 'date':
                    $type = "text";
                    break;
                default:
                    $type = "text";
                    break;
            }

            // Create Field in Table Fields
            $newfield = [
                'form' => 0,
                'user_id' => 0,
                'label' => $label = ucwords(str_replace('_', ' ', $field['name'])),
                'table' => 'customer',
                'field' => $field['name'],
                'type' => $type,
            ];
            FilTableFields::create($newfield);
            $newfield['form']=1;
            FilTableFields::create($newfield);

        }
        return $result;
    }
    private static function createMigrationFile(array $field)
    {
        $table = 'fil_customers'; // z. B. von der Resource ableiten

        $migrationName = 'add_' . $field['name'] . '_to_' . $table . '_table';

        $migrationCommand = 'make:migration ' . $migrationName . ' --table=' . $table;
        Artisan::call($migrationCommand);

        // Migration anpassen
        $path = $path = collect(glob(database_path('migrations/*.php')))
            ->filter(fn ($f) => str_contains($f, $migrationName))
            ->first();

        $stub = self::generateFieldLine($field);
        file_put_contents($path, str_replace('//', $stub, file_get_contents($path)));
        return $path;
    }

    protected static function generateFieldLine(array $field): string
    {
        $line = "\$table->{$field['type']}('{$field['name']}'";

        if (!empty($field['length']) && $field['type'] === 'string') {
            $line .= ", {$field['length']}";
        }

        $line .= ");";

        return "            {$line}\n";
    }

    private static function execMigration($path){
        $command  = "migrate";
        $status = "";
        try{
            Artisan::call($command);
            $status = "Success";
        }
        catch(\Exception $e){
            $output = $e->getMessage();
            $status = "Fail";
        }
        finally{
            $output = Artisan::output();
            $result['status'] = $status;
            $result['output'] = $output;
            return $result;
        }
    }

    private function createCrmField($field){

    }
}
