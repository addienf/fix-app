<?php

namespace App\Models\Engineering\Maintenance\ChamberG2;

use App\Models\Engineering\Maintenance\ChamberG2\Pivot\ChamberG2Detail;
use App\Models\Engineering\Maintenance\ChamberG2\Pivot\ChamberG2PIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberG2 extends Model
{
    use HasFactory;

    protected $table = 'chamber_g2s';

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
        return $this->hasOne(ChamberG2Detail::class, 'g2_id');
    }

    public function pic()
    {
        return $this->hasOne(ChamberG2PIC::class, 'g2_id');
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
