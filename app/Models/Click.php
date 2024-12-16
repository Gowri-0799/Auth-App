<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    protected $table = 'clicks';

    
    protected $fillable = [
        'click_id',
        'click_source',
        'click_ts',
        'partners_affiliates_id',
        'zip',
        'state',
        'city',
        'intended_zip',
        'intended_state',
        'intended_city',
        'channel',
        'affiliate_source_url',
    ];

    protected $casts = [
        'click_ts' => 'datetime',
    ];

}
