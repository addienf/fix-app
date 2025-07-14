<?php

namespace App\Models\Engineering\Maintenance\WalkinChamber;

use App\Models\Engineering\Maintenance\WalkinChamber\Pivot\WalkinChamberDetail;
use App\Models\Engineering\Maintenance\WalkinChamber\Pivot\WalkinChamberPIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinChamber extends Model
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
        return $this->hasOne(WalkinChamberDetail::class, 'walk_in_id');
    }

    public function pic()
    {
        return $this->hasOne(WalkinChamberPIC::class, 'walk_in_id');
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
