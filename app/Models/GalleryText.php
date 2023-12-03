<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GalleryPics;

class GalleryText extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function galleryPics(){
        return $this->belongsTo(GalleryPics::Class, 'id', 'pic_id');
    }

}
