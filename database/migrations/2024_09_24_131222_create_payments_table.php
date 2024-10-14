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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method_id')->unique(); 
            $table->string('type'); 
            $table->string('zoho_cust_id'); 
            $table->string('last_four_digits', 4)->nullable(); 
            $table->integer('expiry_year')->nullable(); 
            $table->integer('expiry_month')->nullable(); 
            $table->string('payment_gateway')->nullable(); 
            $table->string('status')->default('active');
            $table->string('payment_mode');
            $table->decimal('amount', 10, 2);
            $table->string('invoice_id')->unique();
            $table->string('payment_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
