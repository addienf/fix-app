<?php

namespace App\Models\Warehouse\Pelabelan\Pivot;

use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QCPassedDetail extends Model
{
    use HasFactory;

    protected $table = 'qc_passed_details';

    protected $fillable = [
        'qc_passed_id',
        'nama_produk',
        'tipe',
        'serial_number',
        'jenis_transaksi',
        'jumlah',
        'keterangan'
    ];

    public function qc()
    {
        return $this->belongsTo(QCPassed::class, 'qc_passed_id');
    }
}
