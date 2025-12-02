<?php

namespace App\Models\Engineering\Permintaan\Pivot;

use App\Models\Engineering\Permintaan\PermintaanSparepart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanSparepartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sparepart_id',
        'bahan_baku',
        'spesifikasi',
        'jumlah',
        'keperluan_barang'
    ];

    public function permintaanSparepart()
    {
        return $this->belongsTo(PermintaanSparepart::class, 'sparepart_id');
    }
}
