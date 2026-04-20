<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'isi_laporan',
        'file_laporan',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}
