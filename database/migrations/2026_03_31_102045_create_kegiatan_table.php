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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organisasi_id')
                ->constrained('organisasi')
                ->cascadeOnDelete();

            $table->string('nama_kegiatan', 150);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->string('tempat', 150)->nullable();
            $table->string('proposal', 255)->nullable();
            $table->string('keterangan')->nullable();

            $table->enum('status', ['pending', 'disetujui pembina', 'disetujui admin', 'ditolak pembina', 'ditolak admin'])
                ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
