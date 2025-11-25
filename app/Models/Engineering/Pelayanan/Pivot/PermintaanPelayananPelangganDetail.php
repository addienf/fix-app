<?php

namespace App\Models\Engineering\Pelayanan\Pivot;

use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPelayananPelangganDetail extends Model
{
    use HasFactory;

    protected $table = 'pelayanan_pelanggan_details';

    protected $fillable = [
        'pelayanan_id',
        'nama_alat',
        'tipe',
        'nomor_seri',
        'deskripsi',
        'quantity'
    ];

    public function pelayananPelanggan()
    {
        return $this->belongsTo(PermintaanPelayananPelanggan::class, 'pelayanan_id');
    }
}
