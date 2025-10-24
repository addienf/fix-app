<?php

namespace App\Models\Production\Jadwal\Pivot;

use App\Models\Production\Jadwal\JadwalProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentifikasiProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_produksi_id',
        'nama_alat',
        'tipe',
        'no_seri',
        'custom_standar',
        'jumlah',
    ];

    public function jadwalProduksi()
    {
        return $this->belongsTo(JadwalProduksi::class);
    }
}
