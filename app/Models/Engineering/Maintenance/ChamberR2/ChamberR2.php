<?php

namespace App\Models\Engineering\Maintenance\ChamberR2;

use App\Models\Engineering\Maintenance\ChamberR2\Pivot\ChamberR2Detail;
use App\Models\Engineering\Maintenance\ChamberR2\Pivot\ChamberR2PIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberR2 extends Model
{
    use HasFactory;

    protected $table = 'chamber_r2s';

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
        return $this->hasOne(ChamberR2Detail::class, 'r2_id');
    }

    public function pic()
    {
        return $this->hasOne(ChamberR2PIC::class, 'r2_id');
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
