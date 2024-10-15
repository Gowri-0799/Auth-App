<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $table = 'supports';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'date',
        'request_type',
        'subscription_number',
        'message',
        'status',
        'zoho_cust_id',
        'zoho_cpid',
        'comments',
        'attributes',
    ];
}
