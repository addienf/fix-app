<?php

namespace App\Models\Production\Jadwal\Pivot;

use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberDaya extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_produksi_id',
        'bahan_baku',
        'spesifikasi',
        'jumlah',
        'status',
        'keperluan',
    ];

    public function jadwalProduksi()
    {
        return $this->belongsTo(JadwalJadwalProduksi::class);
    }
}
