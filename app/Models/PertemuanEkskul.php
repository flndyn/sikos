<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PertemuanEkskul extends Model
{
    use HasFactory;

    protected $table = 'pertemuan_ekskul';

    protected $fillable = [
        'organisasi_id',
        'pembina_id',
        'tanggal',
        'pertemuan_ke',
        'semester',
        'tahun_ajaran',
        'deskripsi_kegiatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiEkskul::class, 'pertemuan_id');
    }

    public function fotoKegiatan(): HasMany
    {
        return $this->hasMany(FotoPertemuanEkskul::class, 'pertemuan_id');
    }
}
