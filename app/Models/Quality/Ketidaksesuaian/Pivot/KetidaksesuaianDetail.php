<?php

namespace App\Models\Quality\Ketidaksesuaian\Pivot;

use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetidaksesuaianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'ketidaksesuaian_id',
        'nama_produk',
        'serial_number',
        'ketidaksesuaian',
        'jumlah',
        'satuan',
        'keterangan',
    ];

    public function ketidaksesuaian()
    {
        return $this->belongsTo(Ketidaksesuaian::class, 'ketidaksesuaian_id');
    }
}
