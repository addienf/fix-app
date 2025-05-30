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
        'mesin_yang_digunakan',
        'tenaga_kerja',
        'bahan_baku',
    ];

    protected $casts = [
        'bahan_baku' => 'array',
    ];

    public function jadwalProduksi()
    {
        return $this->belongsTo(JadwalJadwalProduksi::class);
    }
}
