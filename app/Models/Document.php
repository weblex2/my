<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = ['id'];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'external_id', 'external_id');
    }
}
