<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organisasi extends Model
{
    use HasFactory;

    protected $table = 'organisasi';

    protected $fillable = [
        'nama_organisasi',
        'deskripsi',
        'pembina_id',
        'ketua_id',
    ];

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }

    public function ketua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_id');
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'organisasi_id');
    }
}