<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WegDoc extends Model
{
   protected $guarded = ['id'];

   public function attachments(){
      return $this->hasMany(WegDocAttachment::class, 'message_id', 'message_id')
        ->select(['id', 'message_id', 'filename', 'content_type','size','created_at','updated_at']);
   }

   public function attachements(){
      return $this->hasMany(WegDocAttachment::class, 'message_id', 'message_id')
        ->select(['id', 'message_id', 'filename', 'content_type','size','created_at','updated_at']);
   }
}
