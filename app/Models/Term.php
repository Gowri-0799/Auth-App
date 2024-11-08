<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $table = 'terms';

    protected $fillable = [
        'zoho_cust_id',
        'zoho_cpid',
        'subscription_number',
        'ip_address',
        'browser_agent',
        'consent',
        'plan_name',
        'amount',
    ];

}
