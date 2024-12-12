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
        Schema::table('partner_users', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->string('invitation_status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_users', function (Blueprint $table) {
            $table->string('status')->change();
            $table->string('invitation_status')->change();
        });
    }
};
