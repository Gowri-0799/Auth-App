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
        Schema::create('creditnotes', function (Blueprint $table) {
            $table->id();
            $table->string('creditnote_id')->unique(); // Unique credit note ID
            $table->string('creditnote_number')->nullable(); // Credit note number (optional)
            $table->timestamp('credited_date')->nullable(); // Date when the credit was issued
            $table->string('invoice_number')->nullable(); // Related invoice number (optional)
            $table->string('zoho_cust_id'); // Zoho customer ID
            $table->string('status')->nullable(); // Credit note status
            $table->decimal('credited_amount', 10, 2)->default(0); // Amount credited
            $table->decimal('balance', 10, 2)->default(0); // Remaining balance
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditnotes');
    }
};
