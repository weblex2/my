<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryPicContent extends Model
{
    use HasFactory;

    protected $table = 'gallery_pic_content';
    protected $guarded = ["id"];
}
