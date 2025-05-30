<?php

namespace App\Models\Warehouse\PermintaanBahanWBB\Pivot;

use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBahanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_bahan_wbb_id',
        'bahan_baku',
        'spesifikasi',
        'jumlah',
        'keperluan_barang',
    ];

    public function permintaanBahanWBB()
    {
        return $this->belongsTo(PermintaanBahan::class, 'permintaan_bahan_wbb_id');
    }
}
