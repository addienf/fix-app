<?php

namespace App\Models\Production\PermintaanBahanProduksi\Pivot;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $id
 * @property string|null $permintaan_bahan_id
 * @property string|null $bahan_baku
 * @property string|null $spesifikasi
 * @property string|null $jumlah
 * @property string|null $keperluan_barang
 */
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
