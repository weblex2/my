<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "fil_customers";
    
    protected $fillable = [
        'name', 'email', 'phone', 'date_of_birth', 'address',
        'zip_code', 'city'
    ];

    public function customer_address()  {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function company()  {
        return $this->belongsTo(Company::class);
    }

    public function primaryAddress() {
        return $this->belongsTo(CustomerAddress::class, 'primary_address');
    }
}
