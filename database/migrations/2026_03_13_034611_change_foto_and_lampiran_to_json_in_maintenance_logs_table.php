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
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->json('foto')->nullable()->change(); // Change to JSON for multiple files
            $table->json('lampiran')->nullable()->change(); // Change to JSON for multiple files
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            $table->string('foto')->nullable()->change(); // Revert to string
            $table->string('lampiran')->nullable()->change(); // Revert to string
        });
    }
};
