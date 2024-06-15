<?php

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PDFData extends Model
{
    protected $table="PDFGenerate";
    protected $fillable = ['show_name', 'series', 'lead_actor'];
}
