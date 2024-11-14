<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
 /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'invoice_date',
        'invoice_number',
        'subscription_id',
        'credits_applied',
        'discount',
        'payment_made',
        'payment_method',
        'invoice_link',
        'zoho_cust_id',
        'invoice_items',
        'balance',
        'payment_details',
        'status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'invoice_date' => 'datetime',
        'credits_applied' => 'decimal:2',
        'discount' => 'decimal:2',
        'payment_made' => 'decimal:2',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Relationship with the Subscription model.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'subscription_id');
    }

    /**
     * Relationship with the Customer model (assuming you have a Customer model).
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'zoho_cust_id', 'zoho_cust_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id'); // Adjust the foreign and local keys if necessary
    }
}