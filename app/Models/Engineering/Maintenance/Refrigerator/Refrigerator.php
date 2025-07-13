<?php

namespace App\Models\Engineering\Maintenance\Refrigerator;

use App\Models\Engineering\Maintenance\Refrigerator\Pivot\RefrigeratorDetail;
use App\Models\Engineering\Maintenance\Refrigerator\Pivot\RefrigeratorPIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refrigerator extends Model
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
        return $this->hasOne(RefrigeratorDetail::class, 'refrigerator_id');
    }

    public function pic()
    {
        return $this->hasOne(RefrigeratorPIC::class, 'refrigerator_id');
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
    }
}
