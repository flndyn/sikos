<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoPertemuanEkskul extends Model
{
    use HasFactory;

    protected $table = 'foto_pertemuan_ekskul';

    protected $fillable = [
        'pertemuan_id',
        'file_path',
        'keterangan',
    ];

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(PertemuanEkskul::class, 'pertemuan_id');
    }
}
