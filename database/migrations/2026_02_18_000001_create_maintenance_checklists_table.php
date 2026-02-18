<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('perangkat'); // UPS, AC, Server, Switch, Router, Listrik
            $table->integer('q1')->default(0)->comment('Q1 - Jan, Feb, Mar');
            $table->integer('q2')->default(0)->comment('Q2 - Apr, May, Jun');
            $table->integer('q3')->default(0)->comment('Q3 - Jul, Aug, Sep');
            $table->integer('q4')->default(0)->comment('Q4 - Oct, Nov, Dec');
            $table->enum('status_q1', ['belum', 'proses', 'selesai'])->nullable();
            $table->enum('status_q2', ['belum', 'proses', 'selesai'])->nullable();
            $table->enum('status_q3', ['belum', 'proses', 'selesai'])->nullable();
            $table->enum('status_q4', ['belum', 'proses', 'selesai'])->nullable();
            $table->date('tanggal_q1')->nullable();
            $table->date('tanggal_q2')->nullable();
            $table->date('tanggal_q3')->nullable();
            $table->date('tanggal_q4')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_checklists');
    }
};
