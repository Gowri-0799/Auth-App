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
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('addon_price', 10, 2)->nullable()->change();
            $table->string('addon_name')->nullable()->change();
            $table->string('addon_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('addon_price', 10, 2)->nullable(false)->change();
            $table->string('addon_name')->nullable(false)->change();
            $table->string('addon_code')->nullable(false)->change();
        });
    }
};
