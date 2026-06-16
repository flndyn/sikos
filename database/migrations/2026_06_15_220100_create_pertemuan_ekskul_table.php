<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuan_ekskul', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organisasi_id')
                ->constrained('organisasi')
                ->cascadeOnDelete();

            $table->foreignId('pembina_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('tanggal');
            $table->unsignedInteger('pertemuan_ke');
            $table->string('semester', 10);
            $table->string('tahun_ajaran', 15);
            $table->text('deskripsi_kegiatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan_ekskul');
    }
};
