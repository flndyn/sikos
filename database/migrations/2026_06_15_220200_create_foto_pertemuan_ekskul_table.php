<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_pertemuan_ekskul', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pertemuan_id')
                ->constrained('pertemuan_ekskul')
                ->cascadeOnDelete();

            $table->string('file_path', 255);
            $table->string('keterangan', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_pertemuan_ekskul');
    }
};
