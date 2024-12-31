<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'refund_id',
        'creditnote_id',
        'balance_amount',
        'refund_amount',
        'description',
        'zoho_cust_id',
        'creditnote_number',
        'status',
        'gateway_transaction_id',
        'refund_mode',
        'payment_method_id',
        'parent_payment_id',
    ];
}
