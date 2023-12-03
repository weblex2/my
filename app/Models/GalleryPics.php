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
        return $this->belongsTo(Gallery::Class, 'gal_id', 'id');
    }

    public function GalleryText(){
        return $this->hasMany(GalleryText::Class, 'pic_id', 'id')->where('language','=', session('lang'));
    }
}
