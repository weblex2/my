<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'number', 'total_price', 'shipping_price', 'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::Class);
    }

    public function items()  {
        return $this->hasMany(OrderItem::class);
    }

}
