<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Entfernen Sie die belongsToMany-Beziehung
    public function quoteProducts()
    {
        return $this->hasMany(QuoteProduct::class);
    }

    // Accessor für total_amount
    public function getTotalAmountAttribute()
    {
        if (!$this->relationLoaded('quoteProducts')) {
            $this->load('quoteProducts');
        }

        return $this->quoteProducts->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
