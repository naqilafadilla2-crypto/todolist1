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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->foreignId('rack_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rack_unit')->nullable()->comment('Position in rack (1-42 for standard 19" rack)');
            $table->integer('height_units')->default(1)->comment('Height in U (rack units)');
            $table->text('description')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
