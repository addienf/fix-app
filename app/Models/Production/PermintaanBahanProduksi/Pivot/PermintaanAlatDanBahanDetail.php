<?php

namespace App\Models\Production\PermintaanBahanProduksi\Pivot;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanAlatDanBahanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_bahan_id',
        'bahan_baku',
        'spesifikasi',
        'jumlah',
        'keperluan_barang',
    ];

    public function permintaanBahan()
    {
        return $this->belongsTo(PermintaanAlatDanBahan::class, 'permintaan_bahan_id');
    }
}
