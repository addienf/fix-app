<?php

namespace App\Models\Engineering\Permintaan;

use App\Models\Engineering\Permintaan\Pivot\PermintaanSparepartDetail;
use App\Models\Engineering\Permintaan\Pivot\PermintaanSparepartPIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanSparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'tanggal',
        'no_surat',
        'dari',
        'kepada',
        'status_penyerahan'
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanSparepartDetail::class, 'sparepart_id');
    }

    public function pic()
    {
        return $this->hasOne(PermintaanSparepartPIC::class, 'sparepart_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->diketahui_ttd &&
                $model->status_penyerahan !== 'Diketahui'
            ) {
                $model->status_penyerahan = 'Diketahui';
            }

            if (
                $model->pic?->diserahkan_ttd &&
                $model->status_penyerahan !== 'Diserahkan'
            ) {
                $model->status_penyerahan = 'Diserahkan';
            }
        });

        static::deleting(function ($model) {
            // if ($model->detail) {
            //     $model->detail->delete();
            // }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
