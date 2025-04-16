<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Column;

class LaravelColumnCreator extends Controller
{

    public $col;
    public $columns;

    public function createModifiedFields($columns){
        foreach ($columns as $i => $column){
            $col = new Column();
            $col->setName($column['column_name']);
            $col->setType($column['datatype']);
            $col->setLength($column['length']);
            $col->setCollation($column['collation']);
            $col->setNullable($column['nullable']);
            $columns[$i] = $col->getColsString();
        }
        $txt = implode("\n", $columns);
        return $txt;
    }
}
