<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryPics extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function gallery(){
        return $this->belongsTo(Gallery::Class, 'gal_id', 'id');
    }
}
