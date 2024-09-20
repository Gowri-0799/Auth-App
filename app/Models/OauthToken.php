<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OauthToken extends Model
{
    protected $fillable = ['access_token', 'refresh_token', 'expires_at'];

    protected $dates = ['expires_at'];
}
