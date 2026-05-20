<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create pivot table
        Schema::create('organisasi_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisasi_id')->constrained('organisasi')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['ketua', 'pembina']);
            $table->timestamps();

            $table->unique(['organisasi_id', 'user_id', 'role']);
        });

        // 2. Migrate existing data from old columns to pivot table
        $organisasiRows = DB::table('organisasi')
            ->select('id', 'pembina_id', 'ketua_id')
            ->get();

        foreach ($organisasiRows as $row) {
            if ($row->pembina_id) {
                DB::table('organisasi_user')->insert([
                    'organisasi_id' => $row->id,
                    'user_id' => $row->pembina_id,
                    'role' => 'pembina',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if ($row->ketua_id) {
                DB::table('organisasi_user')->insert([
                    'organisasi_id' => $row->id,
                    'user_id' => $row->ketua_id,
                    'role' => 'ketua',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Drop old foreign key columns
        Schema::table('organisasi', function (Blueprint $table) {
            $table->dropForeign(['pembina_id']);
            $table->dropForeign(['ketua_id']);
            $table->dropColumn(['pembina_id', 'ketua_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add old columns
        Schema::table('organisasi', function (Blueprint $table) {
            $table->foreignId('pembina_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ketua_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // Migrate data back from pivot
        $pivotRows = DB::table('organisasi_user')->get();
        foreach ($pivotRows as $row) {
            if ($row->role === 'pembina') {
                DB::table('organisasi')->where('id', $row->organisasi_id)->update(['pembina_id' => $row->user_id]);
            }
            if ($row->role === 'ketua') {
                DB::table('organisasi')->where('id', $row->organisasi_id)->update(['ketua_id' => $row->user_id]);
            }
        }

        Schema::dropIfExists('organisasi_user');
    }
};
