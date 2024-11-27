<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('partner_users', function (Blueprint $table) {
            $table->id(); 
            $table->string('zoho_cust_id');
            $table->string('zoho_cpid')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('status')->default('active');
            $table->string('invitation_status')->default('pending');
            $table->string('password')->nullable(); 
            $table->timestamp('userLastLoggedin')->nullable();
            $table->timestamps(); 
            $table->boolean('is_primary')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_users');
    }
};
