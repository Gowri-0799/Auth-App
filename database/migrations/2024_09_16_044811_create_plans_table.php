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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->decimal('plan_price', 8, 2);
            $table->string('plan_code');
            $table->decimal('addon_price', 10, 2);
            $table->string('addon_name');
            $table->string('plan_id')->nullable();
            $table->string('addon_code')->unique();

            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
