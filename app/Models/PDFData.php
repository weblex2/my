<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfData extends Model
{
    protected $table="PDFGenerate";
    protected $fillable = ['show_name', 'series', 'lead_actor'];
}
