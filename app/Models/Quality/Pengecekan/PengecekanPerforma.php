<?php

namespace App\Models\Quality\Pengecekan;

use App\Models\Production\SPK\SPKQuality;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\Quality\Pengecekan\Pivot\PengecekanPerformaDetail;
use App\Models\Quality\Pengecekan\Pivot\PengecekanPerformaPIC;
use App\Models\Quality\Release\ProductRelease;
use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanPerforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_qualities_id',
        'tipe',
        'volume',
        'serial_number',
        'note',
        'status_penyelesaian',
    ];

    public function spkQC()
    {
        return $this->belongsTo(SPKQuality::class, 'spk_qualities_id');
    }

    public function pic()
    {
        return $this->hasOne(PengecekanPerformaPIC::class, 'pengecekan_performa_id');
    }

    public function ketidaksesuaian()
    {
        return $this->hasOne(Ketidaksesuaian::class, 'pengecekan_performa_id');
    }

    public function productRelease()
    {
        return $this->hasOne(ProductRelease::class, 'pengecekan_performa_id');
    }

    public function qcPassed()
    {
        return $this->hasOne(QCPassed::class, 'pengecekan_performa_id');
    }

    public function detail()
    {
        return $this->hasOne(PengecekanPerformaDetail::class, 'pengecekan_performa_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->accepted_signature &&
                $model->status_penyelesaian !== 'Diterima'
            ) {
                $model->status_penyelesaian = 'Diterima';
            }

            if (
                $model->pic?->approved_signature &&
                $model->status_penyelesaian !== 'Disetujui'
            ) {
                $model->status_penyelesaian = 'Disetujui';
            }
        });

        static::deleting(function ($model) {
            if ($model->detail) {
                $model->detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
