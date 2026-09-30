<?php

namespace App\Models\MR\Perubahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPerubahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perubahan_id',
        'nomor_dok_lama',
        'nomor_rev_lama',
        'judul_dok_lama',
        'nomor_dok_baru',
        'nomor_rev_baru',
        'judul_dok_baru',
        'dokumen_terkait',
        'uraian_perubahan',
    ];

    public function perubahanInformasi()
    {
        return $this->belongsTo(PerubahanInformasi::class, 'perubahan_id');
    }
}
