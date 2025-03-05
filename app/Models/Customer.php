<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'phone', 'date_of_birth', 'address',
        'zip_code', 'city'
    ];

    
}
