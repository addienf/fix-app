<?php

namespace App\Models\Engineering\Service;

use App\Models\Engineering\Service\Pivot\ServiceReportDetail;
use App\Models\Engineering\Service\Pivot\ServiceReportPIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'produk_name',
        'type',
        'serial_number',
        'status_warranty',
        'remarks',
        'status_penyetujuan',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function detail()
    {
        return $this->hasOne(ServiceReportDetail::class, 'service_id');
    }

    public function pic()
    {
        return $this->hasOne(ServiceReportPIC::class, 'service_id');
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
