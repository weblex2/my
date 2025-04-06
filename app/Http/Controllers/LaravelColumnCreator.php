<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaravelColumnCreator extends Controller
{

    public $cols = [];
    private $col;

    public function __construct(){

    }

    public static function createModifiedFields($columns){
        foreach ($columns as $column){
            $this->setName($column['name']);
            $this->setType($column['datatype']);
            $this->setLength($column['length']);
            $this->setNullable($column['nullable']);
            $this->cols[] = $col;
        }
        return this->cols;
    }

    private function setName($name){
        $this->name = $name;
    }

    private function setType($type){
        $this->type = $type;
    }

    private function setLengrh($length){
        $this->length = $length;
    }

    public function setNullable(){

    }
}
