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
        Schema::create('checklist_expired_dates', function (Blueprint $table) {

            $table->id();

            // Nama perangkat
            $table->string('nama_perangkat');

            // Tanggal expired / kadaluarsa
            $table->date('tanggal_kadaluarsa');

            // Status otomatis:
            // HIJAU / KUNING / MERAH
            $table->enum('status', [
                'HIJAU',
                'KUNING',
                'MERAH'
            ])->nullable();

            // Peringatan sebelum expired
            // contoh: 7 hari sebelum expired
            $table->integer('peringatan_hari')->default(7);

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_expired_dates');
    }
};