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
        Schema::create('clicks', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('click_id')->nullable(); // Nullable string for click ID
            $table->string('click_source')->nullable(); // Nullable string for source
            $table->timestamp('click_ts')->nullable(); // Nullable timestamp for click time
            $table->unsignedBigInteger('partners_affiliates_id')->nullable(); // Foreign key for partners/affiliates
            $table->string('zip', 10)->nullable(); // Nullable zip with max 10 characters
            $table->string('state', 100)->nullable(); // Nullable state
            $table->string('city', 100)->nullable(); // Nullable city
            $table->string('intended_zip', 10)->nullable(); // Nullable intended zip
            $table->string('intended_state', 100)->nullable(); // Nullable intended state
            $table->string('intended_city', 100)->nullable(); // Nullable intended city
            $table->string('channel')->nullable(); // Nullable channel string
            $table->text('affiliate_source_url')->nullable(); // Nullable URL
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
