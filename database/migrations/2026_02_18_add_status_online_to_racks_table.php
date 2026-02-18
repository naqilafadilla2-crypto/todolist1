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
        Schema::table('racks', function (Blueprint $table) {
            $table->enum('status_online', ['online', 'offline'])->default('offline')->after('total_units');
            $table->timestamp('last_checked_at')->nullable()->after('status_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('racks', function (Blueprint $table) {
            $table->dropColumn(['status_online', 'last_checked_at']);
        });
    }
};
