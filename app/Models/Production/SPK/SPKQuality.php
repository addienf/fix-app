<?php

namespace App\Models\Production\SPK;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\SPK\Pivot\SPKQualityDetail;
use App\Models\Production\SPK\Pivot\SPKQualityPIC;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
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
        'status_penerimaan',
    ];

    public function spkMarketing()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function pic()
    {
        return $this->hasOne(SPKQualityPIC::class, 'spk_qualities_id');
    }

    public function details()
    {
        return $this->hasMany(SPKQualityDetail::class, 'spk_qualities_id');
    }

    public function kelengkapanMaterial()
    {
        return $this->hasOne(KelengkapanMaterialSS::class, 'spk_qualities_id');
    }

    public function pengecekanSS()
    {
        return $this->hasOne(PengecekanMaterialSS::class, 'spk_qualities_id');
    }

    public function pengecekanElectrical()
    {
        return $this->hasOne(PengecekanMaterialElectrical::class, 'spk_qualities_id');
    }

    public function penyerahanProdukJadi()
    {
        return $this->hasOne(PenyerahanProdukJadi::class, 'spk_qualities_id');
    }

    public function pengecekanPerforma()
    {
        return $this->hasOne(PengecekanPerforma::class, 'spk_qualities_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->receive_signature &&
                $model->status_penerimaan !== 'Diterima'
            ) {
                $model->status_penerimaan = 'Diterima';
            }
        });

        static::deleting(function ($spesifikasi) {
            if ($spesifikasi->pic) {
                $spesifikasi->pic->delete();
            }
        });
    }
}
