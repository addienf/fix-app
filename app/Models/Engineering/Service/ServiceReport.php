<?php

namespace App\Models\Engineering\Service;

use App\Models\Engineering\Service\Pivot\ServiceReportDetail;
use App\Models\Engineering\Service\Pivot\ServiceReportPIC;
use App\Models\Engineering\Service\Pivot\ServiceReportProduk;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'form_no',
        'tanggal',
        'name_complaint',
        'company_name',
        'address',
        'phone_number',
        'service_category',
        'actions',
        'service_fields',
        'status_penyetujuan',
    ];

    protected $casts = [
        'service_category' => 'array',
        'actions' => 'array',
        'service_fields' => 'array',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function details()
    {
        return $this->hasMany(ServiceReportDetail::class, 'service_id');
    }

    public function produkServices()
    {
        return $this->hasMany(ServiceReportProduk::class, 'service_id');
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

        static::saved(function () {
            SPKService::clearModelCaches();
        });

        static::deleted(function () {
            SPKService::clearModelCaches();
        });
    }
}
