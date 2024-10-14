<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Customer extends Authenticatable
{
    use HasFactory,Notifiable;
    protected $table = 'customers';
    protected $fillable = [
        'customer_name',
        'customer_email',
        'first_name',
        'last_name',
        'password',
        'zohocust_id', // If you allow this to be mass-assigned
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
        'company_name'
    ];
}
