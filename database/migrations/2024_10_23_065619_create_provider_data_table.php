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
        Schema::create('provider_data', function (Blueprint $table) {
            $table->id();
            $table->string('zoho_cust_id')->nullable(); 
            $table->string('uploaded_by')->nullable(); 
            $table->string('file_name')->nullable(); 
            $table->integer('file_size')->nullable(); 
            $table->integer('zip_count')->nullable(); 
            $table->string('url')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_data');
    }
};
