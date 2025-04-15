<?php

namespace App\Classes;



class Column {

    private $col;

    public function __contruct($col){
        $this->col  = $col;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setType($type){
        $this->type = $type;
    }

    public function setLength($length){
        $this->length = $length;
    }

    public function setNullable($nullable){
        if ($nullable){
            $this->nullable = true;
        }
    }

    public function setCollation($collation){
        if (trim($collation)!=""){
            $this->collation = $collation;
        }
    }

    public function getColsString(){
        $txt = "";

        $length = trim($this->length)!="" ? ','.$this->length : '';
        $nullable = isset($this->nullable) ? '->nullable()' : '';
        $collation = isset($this->collation) ? '->collation(\''.$this->collation.'\')' : '';
        $txt .= '$table->'.
                $this->type.'(\'' . $this->name .'\''.$length.')'.
                $nullable.
                $collation.";";
        return $txt;
    }
}


