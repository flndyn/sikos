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
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->foreignId('penanggung_jawab')->nullable()->after('tempat')->constrained('users')->nullOnDelete();
            $table->date('tanggal_berakhir')->nullable()->after('penanggung_jawab');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['penanggung_jawab']);
            $table->dropColumn(['penanggung_jawab', 'tanggal_berakhir']);
        });
    }
};
