<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_id')->unique(); // Unique Zoho subscription id
            $table->string('subscription_number')->nullable(); // Zoho subscription number
            $table->string('plan_id'); // Plan identifier
            $table->string('invoice_id')->nullable(); // Invoice identifier
            $table->string('payment_method_id')->nullable(); // Payment method identifier
            $table->timestamp('next_billing_at')->nullable(); // Next billing date
            $table->dateTime('start_date'); // Subscription start date
            $table->string('zoho_cust_id'); // Zoho customer ID
            $table->boolean('is_metered_billing')->nullable(); // Indicates if metered billing is used
            $table->string('addon')->nullable(); // Addon details
            $table->boolean('is_custom')->nullable(); // Indicates if the subscription is custom
            $table->string('status')->nullable(); // Subscription status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
