<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'parent_id', 'is_visible', 'description'
    ];

    public function parent(): BelongsTo {
        return $this->belongsTo('Category::class', 'parent_id');
    }

    public function child() : HasMany  {
        return $this->hasMany('Category::class', 'parent_id');
    }

    public function product() :BelongsToMany  {
        return $this->belongsToMany('Product::class', 'parent_id');
    }

}
