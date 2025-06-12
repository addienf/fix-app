<?php

namespace App\Models\Production\Penyerahan\Pivot;

use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyerahanProdukJadiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_jadi_id',
        'nama_produk',
        'tipe',
        'volume',
        'no_seri',
        'jumlah',
        'no_spk'
    ];

    public function produkJadi()
    {
        return $this->belongsTo(PenyerahanProdukJadi::class, 'produk_jadi_id');
    }
}
