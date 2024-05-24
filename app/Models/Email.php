<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $fillable = [
        'recipient',
        'amount',
        'payment_note',
        'identifier',
        'status',
        'from',
        'subject',
        'app',
        'refund-amount',
        'refund-note',
        'date'
    ];
}
