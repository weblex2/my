<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'booking_date',
        'value_date',
        'booking_text',
        'purpose',
        'counterparty',
        'counterparty_iban',
        'counterparty_bic',
        'amount',
        'currency',
        'info',
        'category'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'value_date' => 'date',
        'amount' => 'decimal:2'
    ];
}
