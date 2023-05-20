<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friesenpics extends Model
{
    use HasFactory;

    protected $table = 'Friesenpics';

    public function friese(){
        return $this->belongsTo(Friesen::Class);
    }
}
