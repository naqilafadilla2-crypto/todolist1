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
        Schema::create('app_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');      // Nama kartu (Website, Pasti, dll)
            $table->string('url');       // Link tujuan
            $table->string('image')->nullable(); // Path logo (opsional)
            $table->integer('order')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_links');
    }
};
