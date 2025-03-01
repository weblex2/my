<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id', 'name', 'slug', 'sku', 'description',
        'image','quantity', 'is_visible','is_featured', 
        'type','published_at'
    ];

    public function brand() :BelongsTo 
    {
        return $this->belongsTo('Brand::Class');
    }

    public function categories() :belongsToMany 
    {
        return $this->belongsToMany('Category::Class')->withTimestamps();
    }

}
