<?php
namespace App\MyClasses;

class Dollar{
    
    public $amount;

    function __construct($amount){
        $this->amount = $amount;
    }

    function multiply($times){
        return new Dollar($this->amount * $times);
    } 

    function divide($times){
        if ($times==0) return false;
        else return new Dollar($this->amount / $times);
    }
}

?>