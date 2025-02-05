<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $table = 'partners';
    protected $fillable = [
        'customer_name',
        'zohocust_id', 
        'status',
        'billing_attention',
        'billing_street',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zip',
        'billing_fax',
        'shipping_attention',
        'shipping_street',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zip',
        'shipping_fax',
        'company_name',
        'plan_code',
        
    ];

    protected $casts = [
        'plan_code' => 'array',
    ];
}
