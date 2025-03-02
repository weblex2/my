<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Brand extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id', 'name', 'slug', 'sku', 'description',
        'image', 'quantity', 'is_visible', 'is_featured', 
        'type', 'published_at'
    ];

    public function categories(): BelongsToMany 
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }   

}
