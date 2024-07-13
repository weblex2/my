<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Futter extends Model
{
    protected $table = 'futter';
    protected $guarded = ['id'];

    use HasFactory;

    /* protected $casts = [
        'img' => 'array'
    ]; */


}
