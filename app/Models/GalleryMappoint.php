<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryMappoint extends Model
{
    use HasFactory;
    protected $table = 'gallery_mappoint';
    protected $guarded = ["id"];

    public function gallery(){
        return $this->belongsTo(Gallery::Class, 'country_id', 'code');
    }

    public function GalleryPics(){
        return $this->hasMany(GalleryPics::Class, 'mappoint_id', 'id');
    }
}
