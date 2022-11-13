<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComments extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function blog(){
        return $this->belongsTo(Blog::Class);
    }

    public function comment_user(){
        return $this->belongsTo(User::Class, 'user_id', 'id');
    }
}
