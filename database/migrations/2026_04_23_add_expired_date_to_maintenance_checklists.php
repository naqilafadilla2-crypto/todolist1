<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_checklists', function (Blueprint $table) {
            $table->date('expired_date')->nullable()->after('keterangan')->comment('Tanggal kadaluarsa / akhir masa berlaku perangkat');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_checklists', function (Blueprint $table) {
            $table->dropColumn('expired_date');
        });
    }
};
