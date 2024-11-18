<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerAffiliate extends Model
{
    use HasFactory;

    protected $table = 'partner_affiliates';

    // Specify the fillable fields
    protected $fillable = ['partner_id', 'affiliate_id'];

    
}
