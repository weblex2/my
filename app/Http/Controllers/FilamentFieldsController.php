<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\FilTableFields;

use Illuminate\Http\Request;
use Filament\Tables;
use Filament\Forms;
use Illuminate\Support\Facades\DB;


class FilamentFieldsController extends Controller
{
    private $userId;
    private $tableName;
    private $field;
    private $isForm;
    private $config;
    public  $fields;

    function __construct($tableName='', $form=0){
        $this->tableName = $tableName;
        $this->userId  = Auth::id();
        $this->isForm = $form;
    }

    public function index($table=''){
        $table = DB::select('DESCRIBE fil_customers');;
        $table = json_decode(json_encode($table), true);
        $user_id = $this->userId;
        return view('filament.managefields', compact('table','user_id'));
    }

    public function getFields(){
        $tableFields = FilTableFields::where('table',"=", $this->tableName)
                                     ->where('form',"=",$this->isForm)
                                     ->get();
        foreach ($tableFields as $index => $tableField ){
            $this->config = $tableField;
            // Create Form Fields
            if ($this->isForm==1){
                switch ($tableField->type){
                    case "text": {
                        $this->field = Forms\Components\TextInput::make($tableField->field);
                        break;
                    }
                    case "date": {
                        $this->field = Forms\Components\DatePicker::make($tableField->field);
                        break;
                    }
                    case "toggle": {
                        $this->field = Forms\Components\Toggle::make($tableField->field);
                        break;
                    }
                    default: {
                        $this->field = Forms\Components\TextInput::make($tableField->field);
                        break;
                    }

                }
                $this->setLabel();
                $this->setRequired();
                $this->setSearchable();
                //$this->setSortable();
            }
            // Create View Fields
            else{
                switch ($tableField->type){
                    case "text": {
                        $this->field = Tables\Columns\TextColumn::make($tableField->field);
                        break;
                    }
                    case "date": {
                        $this->field = Tables\Columns\TextColumn::make($tableField->field);
                        $this->setDate();
                        break;
                    }
                    case "dateTime": {
                        $this->field = Tables\Columns\TextColumn::make($tableField->field);
                        $this->setDateTime();
                        break;
                    }
                    case "toggle": {
                        $this->field = Tables\Columns\IconColumn::make($tableField->field);
                        $this->setBoolean();
                        break;
                    }
                    case "markdown": {
                        $this->field = Tables\Columns\MarkdownEditor::make($tableField->field);
                        break;
                    }
                }
                $this->setLabel();
            }
            $this->fields[] = $this->field;
        }
        return $this->fields;
    }

    private function setLabel(){
        if ($this->config->label!=""){
            $this->field->label($this->config->label);
        }
        else{
           $this->field->label($this->config->field);
        }
    }

    private function setBoolean(){
        $this->field->boolean();
    }

    private function setRequired(){
        if ($this->config->required){
            $this->field->required();
        }
    }

    private function setSearchable(){
        if ($this->config->seachable){
            $this->field->seachable();
        }
    }

    private function setSortable(){
        if ($this->config->sortable){
            $this->field->sortable();
        }
    }

    private function setToggable(){
        $this->field->toggleable(isToggledHiddenByDefault: true);
    }

    private function setDate(){
        $this->field->date();
    }

    private function setDateTime(){
        $this->field->dateTime();
    }



}
