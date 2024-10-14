<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id',
        'subscription_number',
        'plan_id',
        'invoice_id',
        'payment_method_id',
        'next_billing_at',
        'start_date',
        'zoho_cust_id',
        'is_metered_billing',
        'addon',
        'is_custom',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'next_billing_at' => 'datetime',
        'start_date' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    // Example relationship to Customer (assuming you have a Customer model)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'zoho_cust_id', 'zoho_cust_id');
    }

    // Example relationship to Plan (assuming you have a Plan model)
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'plan_id');
    }
}
