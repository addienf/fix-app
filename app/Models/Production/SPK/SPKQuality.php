<?php

namespace App\Models\Production\SPK;

use App\Models\Production\SPK\Pivot\SPKQualityDetail;
use App\Models\Production\SPK\Pivot\SPKQualityPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKQuality extends Model
{
    use HasFactory;

    protected $table = 'spk_qualities';

    protected $fillable = [
        'spk_marketing_id',
        'no_spk',
        'dari',
        'kepada',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function pic()
    {
        return $this->hasOne(SPKQualityPIC::class, 'spk_qualities_id');
    }

    public function detail()
    {
        return $this->hasOne(SPKQualityDetail::class, 'spk_qualities_id');
    }

    protected static function booted()
    {
        static::deleting(function ($spesifikasi) {
            if ($spesifikasi->pic) {
                $spesifikasi->pic->delete();
            }
        });
    }
}
