<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'organisasi_id',
        'nama_kegiatan',
        'deskripsi',
        'tanggal_mulai',
        'tempat',
        'proposal',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
    ];

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(\App\Models\Dokumentasi::class, 'kegiatan_id');
    }

    public function laporanKegiatan(): HasMany
    {
        return $this->hasMany(\App\Models\LaporanKegiatan::class, 'kegiatan_id');
    }
}