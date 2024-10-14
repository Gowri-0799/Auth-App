<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
  /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_method_id',
        'type',
        'zoho_cust_id',
        'last_four_digits',
        'expiry_year',
        'expiry_month',
        'payment_gateway',
        'status',
        'payment_mode',
        'amount',
        'invoice_id',
        'payment_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiry_year' => 'integer',
        'expiry_month' => 'integer',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Relationship with the Customer model (assuming you have a Customer model).
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'zoho_cust_id', 'zoho_cust_id');
    }
}
