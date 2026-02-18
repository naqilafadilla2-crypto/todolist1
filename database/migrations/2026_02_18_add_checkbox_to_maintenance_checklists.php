<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_checklists', function (Blueprint $table) {
            // Checkbox untuk setiap quarter
            $table->boolean('checked_q1')->default(false)->after('tanggal_q4');
            $table->boolean('checked_q2')->default(false)->after('checked_q1');
            $table->boolean('checked_q3')->default(false)->after('checked_q2');
            $table->boolean('checked_q4')->default(false)->after('checked_q3');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_checklists', function (Blueprint $table) {
            $table->dropColumn(['checked_q1', 'checked_q2', 'checked_q3', 'checked_q4']);
        });
    }
};
