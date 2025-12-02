<?php

namespace App\Models\Warehouse\Peminjaman\Pivot;

use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanAlatDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'peminjaman_alat_id',
        'nama_sparepart',
        'model',
        'jumlah',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanAlat::class);
    }
}
