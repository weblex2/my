<?php

namespace App\Models;
use App\Models\BlogComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id']; 

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(BlogComments::Class)->orderBy('created_at', 'DESC');
    }

    #public function comment_user(){
    #    return $this->hasManyThrough(User::class, BlogComments::class , 'user_id','id','id','user_id');
    #}



    

    
}
