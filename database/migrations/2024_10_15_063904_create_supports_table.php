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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('request_type');
            $table->string('subscription_number');
            $table->text('message');
            $table->string('status');
            $table->string('zoho_cust_id');
            $table->string('zoho_cpid');
            $table->text('comments')->nullable();
            $table->string('attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
