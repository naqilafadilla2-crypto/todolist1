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
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aplikasi');
            $table->string('status'); // hijau, kuning, merah
            $table->date('tanggal');
            $table->string('file')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('type'); // website, email, pasti, forsa, sap
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
