<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Creditnote extends Model
{
    use HasFactory;

    protected $table = 'creditnotes'; 

    protected $fillable = [
        'creditnote_id',
        'creditnote_number',
        'credited_date',
        'invoice_number',
        'zoho_cust_id',
        'status',
        'credited_amount',
        'balance',
    ];
}
