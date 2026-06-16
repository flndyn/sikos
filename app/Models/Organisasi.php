<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisasi extends Model
{
    use HasFactory;

    protected $table = 'organisasi';

    protected $fillable = [
        'nama_organisasi',
        'deskripsi',
    ];

    public function pembinaUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organisasi_user')
            ->wherePivot('role', 'pembina')
            ->withTimestamps();
    }

    public function ketuaUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organisasi_user')
            ->wherePivot('role', 'ketua')
            ->withTimestamps();
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'organisasi_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(AnggotaOrganisasi::class, 'organisasi_id');
    }

    public function pertemuanEkskul(): HasMany
    {
        return $this->hasMany(PertemuanEkskul::class, 'organisasi_id');
    }
}