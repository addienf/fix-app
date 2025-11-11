<?php

namespace App\Models\Engineering\Maintenance\RissingPipette;

use App\Models\Engineering\Maintenance\RissingPipette\Pivot\RissingPipetteDetail;
use App\Models\Engineering\Maintenance\RissingPipette\Pivot\RissingPipettePIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RissingPipette extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'tag_no',
        'remarks',
        'status_penyetujuan',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function detail()
    {
        return $this->hasOne(RissingPipetteDetail::class, 'rissing_id');
    }

    public function pic()
    {
        return $this->hasOne(RissingPipettePIC::class, 'rissing_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->approved_signature &&
                $model->status_penyetujuan !== 'Disetujui'
            ) {
                $model->status_penyetujuan = 'Disetujui';
            }
        });

        static::deleting(function ($spesifikasi) {
            if ($spesifikasi->pic) {
                $spesifikasi->pic->delete();
            }
        });

        static::saved(function () {
            SPKService::clearModelCaches();
        });

        static::deleted(function () {
            SPKService::clearModelCaches();
        });
    }
}
