<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'company_info';

    protected $fillable = [
        'logo_image',
        'landing_page_uri',
        'landing_page_url_spanish',
        'company_name',
        'business_sales_phone_number',
        'residential_sales_phone_number',
        'zoho_cust_id',
        'tune_link',
        'uploaded_by',
    ];
}
