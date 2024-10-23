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
        Schema::create('company_info', function (Blueprint $table) {
            $table->id();
            $table->string('logo_image')->nullable(); 
            $table->string('landing_page_uri')->nullable(); 
            $table->string('landing_page_url_spanish')->nullable(); 
            $table->string('company_name'); 
            $table->string('business_sales_phone_number')->nullable(); 
            $table->string('residential_sales_phone_number')->nullable();
            $table->string('zoho_cust_id')->nullable(); 
            $table->string('tune_link')->nullable(); 
            $table->string('uploaded_by')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_info');
    }
};
