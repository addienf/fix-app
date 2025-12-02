<?php

namespace App\Models\Quality\Release;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengecekan_performa_id',
        'no_order_release',
        'product',
        'batch',
        'remarks',
        'status',
    ];

    public function pic()
    {
        return $this->hasOne(ProductReleasePIC::class, 'release_id');
    }

    public function qcPassed()
    {
        return $this->hasOne(QCPassed::class, 'release_id');
    }

    public function pengecekanPerforma()
    {
        return $this->belongsTo(PengecekanPerforma::class, 'pengecekan_performa_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->dibuat_signature &&
                $model->status !== 'Dibuat'
            ) {
                $model->status = 'Dibuat';
            }

            if (
                $model->pic?->dikonfirmasi_signature &&
                $model->status !== 'Dikonfirmasi'
            ) {
                $model->status = 'Dikonfirmasi';
            }

            if (
                $model->pic?->diterima_signature &&
                $model->status !== 'Diterima'
            ) {
                $model->status = 'Diterima';
            }

            if (
                $model->pic?->diketahui_signature &&
                $model->status !== 'Diketahui'
            ) {
                $model->status = 'Diketahui';
            }
        });
    }
}
