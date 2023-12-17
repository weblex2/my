<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $table = 'gallery';
    protected $guarded = ['id'];

    public function GalleryPics(){
        return $this->hasMany(GalleryPics::Class, 'gallery_id', 'id');
    }
    

    public function GalleryMappoint(){
        return $this->hasMany(GalleryMappoint::Class, 'country_id', 'code');
    }


    // this is the recommended way for declaring event handlers
    public static function boot() {
        parent::boot();
        self::deleting(function($gallery) { // before delete() method call this
             $gallery->GalleryPics()->each(function($pic) {
                $pic->delete(); // <-- direct deletion
             });
             /* $user->posts()->each(function($post) {
                $post->delete(); // <-- raise another deleting event on Post to delete comments
             }); */
             // do the rest of the cleanup...
        });
    }

}
