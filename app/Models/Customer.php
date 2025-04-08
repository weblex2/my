<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "fil_customers";

    protected $guarded = ['id'];

    public function customer_address()  {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    public function company()  {
        return $this->belongsTo(Company::class);
    }

    public function primaryAddress() {
        return $this->belongsTo(CustomerAddress::class, 'primary_address');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function homeAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 'HOME');
    }

    public function invoiceAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('type', 'INVC');
    }

    public function preferredAddress()
    {
        // Priorisiert HOME, dann INVC
        return $this->hasOne(CustomerAddress::class)
            ->whereIn('type', ['HOME', 'INVC'])
            ->orderByRaw("FIELD(type, 'HOME', 'INVC')");
    }

    public static function countByStatus($status)
    {
        return static::where('status', $status)->count();
    }

    // Optional: Scopes für spezifische Stati
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }


}
