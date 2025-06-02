<?php

namespace App\Models\Warehouse\SerahTerima\Pivot;

use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerahTerimaBahanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'serah_terima_bahan_id',
        'bahan_baku',
        'spesifikasi',
        'jumlah',
        'keperluan_barang',
    ];

    public function serahTerima()
    {
        return $this->belongsTo(SerahTerimaBahan::class);
    }
}
