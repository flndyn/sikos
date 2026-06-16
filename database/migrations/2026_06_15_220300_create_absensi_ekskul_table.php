<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_ekskul', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pertemuan_id')
                ->constrained('pertemuan_ekskul')
                ->cascadeOnDelete();

            $table->foreignId('anggota_id')
                ->constrained('anggota_organisasi')
                ->cascadeOnDelete();

            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])
                ->default('alfa');

            $table->timestamps();

            $table->unique(['pertemuan_id', 'anggota_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_ekskul');
    }
};
