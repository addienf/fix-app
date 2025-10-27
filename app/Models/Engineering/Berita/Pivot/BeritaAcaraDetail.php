<?php

namespace App\Models\Engineering\Berita\Pivot;

use App\Models\Engineering\Berita\BeritaAcara;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcaraDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'berita_id',
        'jenis_pekerjaan',
        'produk',
        'serial_number',
        'desc_pekerjaan',
        'status_barang',
    ];

    // protected $casts = [
    //     'nama_teknisi' => 'array',
    //     'detail_pekerjaan' => 'array',
    // ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_id');
    }
}
