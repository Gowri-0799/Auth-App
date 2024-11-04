<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'addon_code',
        'quantity',
        'price',
        'addon_name',
        'unit_name',
    ];
}
