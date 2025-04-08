<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::Class);
    }

    public static function getFirstAddress($customer)
    {
        // Zuerst nach der 'home' Adresse suchen
        $address = $customer->addresses()->where('type', 'home')->first();

        // Falls keine 'home' Adresse gefunden wird, nach der 'invc' Adresse suchen
        if (!$address) {
            $address = $customer->addresses()->where('type', 'invc')->first();
        }

        return $address;
    }



}
