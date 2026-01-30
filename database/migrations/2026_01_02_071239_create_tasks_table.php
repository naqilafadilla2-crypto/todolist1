<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // auto increment primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // bisa diubah sesuai kebutuhan
            $table->date('tanggal')->nullable();
            $table->date('tanggal_cek')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
