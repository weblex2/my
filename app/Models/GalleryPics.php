<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GalleryText;

class GalleryPics extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function gallery(){
        return $this->belongsTo(Gallery::Class, 'gallery_id', 'id');
    }

    public function GalleryPicContent(){
        return $this->hasOne(GalleryPicContent::Class, 'pic_id', 'id')->where('size','=', 'L');
    }

    public function Thumbnail(){
        return $this->hasOne(GalleryPicContent::Class, 'pic_id', 'id')->where('size','=', 'TN');
    }

    public function PicXl(){
        return $this->hasOne(GalleryPicContent::Class, 'pic_id', 'id')->where('size','=', 'XL');
    }

    public function Mappoint(){
        return $this->belongsTo(GalleryMappoint::Class, 'mappoint_id', 'id');
    }

    public function GalleryText(){
        return $this->hasMany(GalleryText::Class, 'pic_id', 'id')->where('language','=', session('lang'));
    }

    public function GalleryTextAll(){
        return $this->hasMany(GalleryText::Class, 'pic_id', 'id');
    }

    public function GalleryMappoint(){
        return $this->belongsTo(GalleryMappoint::Class, 'mappoint_id', 'id');
    }

}
