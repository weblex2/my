<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Friesenpics;

class Friesen extends Model
{
    protected $table="frise";
    use HasFactory;

    public function friesenpics(){
        return $this->hasMany(Friesenpics::Class, 'friesenid', 'id');
    }
}
