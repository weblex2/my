<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyDates extends Model
{
    use HasFactory;

    protected $table = 'my_dates';
    protected $guarded=['id'];
}
