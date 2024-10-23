<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderData extends Model
{
    use HasFactory;
    protected $table = 'provider_data';

    protected $fillable = [
        'zoho_cust_id',
        'uploaded_by',
        'file_name',
        'file_size',
        'zip_count',
        'url',
    ];
}
