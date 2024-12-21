<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutterPerDay extends Model
{

    protected $table = 'futter_per_day';
    protected $guarded = ['id'];
    use HasFactory;
    
    public function Futter(){
         return $this->belongsTo(Futter::Class, 'futter_id', 'id');
    }

}
