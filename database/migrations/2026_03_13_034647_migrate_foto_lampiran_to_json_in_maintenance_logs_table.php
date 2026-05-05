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
        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Add new JSON columns
            $table->json('foto_json')->nullable();
            $table->json('lampiran_json')->nullable();
        });

        // Migrate existing data to JSON format
        \DB::statement("
            UPDATE maintenance_logs
            SET foto_json = CASE
                WHEN foto IS NOT NULL AND foto != '' THEN JSON_ARRAY(foto)
                ELSE NULL
            END,
            lampiran_json = CASE
                WHEN lampiran IS NOT NULL AND lampiran != '' THEN JSON_ARRAY(lampiran)
                ELSE NULL
            END
        ");

        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['foto', 'lampiran']);
            // Rename new columns
            $table->renameColumn('foto_json', 'foto');
            $table->renameColumn('lampiran_json', 'lampiran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Add back string columns
            $table->string('foto_string')->nullable();
            $table->string('lampiran_string')->nullable();
        });

        // Migrate back to string format (take first element if array)
        \DB::statement("
            UPDATE maintenance_logs
            SET foto_string = CASE
                WHEN JSON_TYPE(foto) = 'ARRAY' AND JSON_LENGTH(foto) > 0 THEN JSON_UNQUOTE(JSON_EXTRACT(foto, '$[0]'))
                ELSE NULL
            END,
            lampiran_string = CASE
                WHEN JSON_TYPE(lampiran) = 'ARRAY' AND JSON_LENGTH(lampiran) > 0 THEN JSON_UNQUOTE(JSON_EXTRACT(lampiran, '$[0]'))
                ELSE NULL
            END
        ");

        Schema::table('maintenance_logs', function (Blueprint $table) {
            // Drop JSON columns
            $table->dropColumn(['foto', 'lampiran']);
            // Rename back to original names
            $table->renameColumn('foto_string', 'foto');
            $table->renameColumn('lampiran_string', 'lampiran');
        });
    }
};
