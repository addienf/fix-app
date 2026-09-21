<?php

namespace App\Models\Purchasing\Penerimaan;

use App\Models\Purchasing\Penerimaan\Pivot\PenerimaanBarangDetails;
use App\Models\Purchasing\Penerimaan\Pivot\PenerimaanBarangPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_penerimaan',
        'nama_supplier',
        'alamat_supplier',
        'nomor_po',
        'sesuai',
        'kondisi',
        'catatan',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(PenerimaanBarangDetails::class);
    }

    public function pic()
    {
        return $this->hasOne(PenerimaanBarangPIC::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $ttdPenerima = $model->pic?->diterima_ttd;
            $ttdDiketahui = $model->pic?->diketahui_ttd;

            if ($ttdDiketahui && !$ttdPenerima) {
                $model->status = 'Belum Ditanda Tangan Penerima';
            } elseif ($ttdDiketahui && $ttdPenerima) {
                $model->status = 'Diketahui';
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
