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
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('zoho_cust_id'); 
            $table->string('zoho_cpid')->nullable(); 
            $table->string('subscription_number')->nullable(); 
            $table->ipAddress('ip_address')->nullable(); 
            $table->text('browser_agent')->nullable(); 
            $table->boolean('consent')->default(false)->nullable(); 
            $table->string('plan_name'); 
            $table->decimal('amount', 10, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms');
    }
};
