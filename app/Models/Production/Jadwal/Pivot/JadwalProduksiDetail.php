<?php

namespace App\Models\Production\Jadwal\Pivot;

use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalProduksiDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_produksi_id',
        'nama_produk',
        'tipe',
        'volume',
        'jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function jadwalProduksi()
    {
        return $this->belongsTo(JadwalJadwalProduksi::class);
    }
}
