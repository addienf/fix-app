<?php

namespace App\Models\Quality\Pengecekan;

use App\Models\Quality\Pengecekan\Pivot\PengecekanPerformaDetail;
use App\Models\Quality\Pengecekan\Pivot\PengecekanPerformaPIC;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanPerforma extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'spk_marketing_id',
        'pengecekan_electrical_id',
        'tipe',
        'volume',
        'serial_number',
        'note',
        'status_penyelesaian',
    ];

    // public function spk()
    // {
    //     return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    // }

    public function pengecekanElectrical()
    {
        return $this->belongsTo(PengecekanMaterialElectrical::class, 'pengecekan_electrical_id');
    }

    public function pic()
    {
        return $this->hasOne(PengecekanPerformaPIC::class, 'pengecekan_performa_id');
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
