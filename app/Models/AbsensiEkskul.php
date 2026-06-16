<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiEkskul extends Model
{
    use HasFactory;

    protected $table = 'absensi_ekskul';

    protected $fillable = [
        'pertemuan_id',
        'anggota_id',
        'status',
    ];

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(PertemuanEkskul::class, 'pertemuan_id');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(AnggotaOrganisasi::class, 'anggota_id');
    }
}
