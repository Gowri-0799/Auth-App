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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique(); // Unique Zoho invoice ID
            $table->timestamp('invoice_date'); // Invoice date
            $table->string('invoice_number')->nullable(); // Invoice number (optional)
            $table->string('subscription_id'); // Subscription ID (Foreign key to subscriptions table)
            $table->decimal('credits_applied', 10, 2)->default(0); // Credits applied
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('payment_made', 10, 2); // Payment made amount
            $table->string('payment_method')->nullable(); // Payment method (optional)
            $table->string('invoice_link')->nullable(); // Invoice link (optional)
            $table->string('zoho_cust_id'); // Zoho customer ID
            $table->json('invoice_items')->nullable(); // JSON column for invoice items
            $table->decimal('balance', 10, 2)->default(0); // Balance remaining
            $table->json('payment_details')->nullable(); // JSON column for payment details
            $table->string('status')->nullable(); // Invoice status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
