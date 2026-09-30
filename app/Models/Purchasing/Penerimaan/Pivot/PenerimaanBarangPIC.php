<?php

namespace App\Models\Purchasing\Penerimaan\Pivot;

use App\Models\Purchasing\Penerimaan\Penerimaanbarang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarangPIC extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_barang_pics';

    protected $fillable = [
        'penerimaan_barang_id',
        'diterima_ttd',
        'diterima_name',
        'diketahui_ttd',
        'diketahui_name',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Penerimaanbarang::class);
    }
}
