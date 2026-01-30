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
        Schema::dropIfExists('task');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->date('tanggal');
            $table->string('file');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }
};
