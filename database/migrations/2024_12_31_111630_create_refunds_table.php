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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id(); 
            $table->date('date'); 
            $table->string('refund_id')->nullable(); 
            $table->string('creditnote_id'); 
            $table->decimal('balance_amount', 10, 2)->nullable(); 
            $table->decimal('refund_amount', 10, 2)->nullable(); 
            $table->text('description')->nullable(); 
            $table->string('zoho_cust_id'); 
            $table->string('creditnote_number')->nullable(); 
            $table->string('parent_payment_id')->nullable(); 
            $table->string('status')->nullable();
            $table->string('gateway_transaction_id')->nullable(); 
            $table->string('refund_mode'); 
            $table->string('payment_method_id')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
