<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    protected $table = 'quote_products';

    protected $guarded = ['id'];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
