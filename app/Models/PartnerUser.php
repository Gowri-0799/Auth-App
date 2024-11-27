<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PartnerUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'partner_users';
    protected $fillable = [
        'zoho_cust_id',
        'zoho_cpid',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'status',
        'invitation_status',
        'password',
        'userLastLoggedin',
        'is_primary',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'userLastLoggedin' => 'datetime',
        'is_primary' => 'boolean',
    ];
}
