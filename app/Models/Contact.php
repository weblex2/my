<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['customer_id', 'type', 'details', 'contacted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
