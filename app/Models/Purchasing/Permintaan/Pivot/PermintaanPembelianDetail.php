<?php

namespace App\Models\Purchasing\Permintaan\Pivot;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_pembelian_id',
        'kode_barang',
        'nama_barang',
        // 'jenis_barang',
        'jumlah',
        'keterangan',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class);
    }
}
