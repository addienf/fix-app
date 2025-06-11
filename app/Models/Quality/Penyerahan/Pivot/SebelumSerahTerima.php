<?php

namespace App\Models\Quality\Penyerahan\Pivot;

use App\Models\Quality\Penyerahan\PenyerahanElectrical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SebelumSerahTerima extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyerahan_electrical_id',
        'kondisi_fisik',
        'detail_kondisi_fisik',
        'kelengkapan_komponen',
        'detail_kelengkapan_komponen',
        'dokumen_pendukung',
        'file_pendukung',
    ];

    public function penyerahanElectrical()
    {
        return $this->belongsTo(PenyerahanElectrical::class, 'penyerahan_electrical_id');
    }
}
