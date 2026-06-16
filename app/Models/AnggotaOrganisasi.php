<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnggotaOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'anggota_organisasi';

    protected $fillable = [
        'organisasi_id',
        'nama',
        'kelas',
        'jenis_kelamin',
        'no_hp',
    ];

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(Organisasi::class, 'organisasi_id');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsensiEkskul::class, 'anggota_id');
    }
}
