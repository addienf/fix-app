<?php

namespace App\Models\Purchasing\Penerimaan\Pivot;

use App\Models\Purchasing\Penerimaan\Penerimaanbarang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarangDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'penerimaan_barang_id',
        'nama_material',
        'kode_material',
        'jumlah',
        'satuan',
        'kondisi',
        'status_barang',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Penerimaanbarang::class);
    }
}
