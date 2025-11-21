<?php

namespace App\Models\Production\SPK;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\SPK\Pivot\SPKQualityDetail;
use App\Models\Production\SPK\Pivot\SPKQualityPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKQuality extends Model
{
    use HasFactory;

    protected $table = 'spk_qualities';

    protected $fillable = [
        'penyerahan_electrical_id',
        'no_spk',
        'dari',
        'kepada',
        'status_penerimaan',
    ];

    public function penyerahanElectrical()
    {
        return $this->belongsTo(PenyerahanElectrical::class, 'penyerahan_electrical_id');
    }

    public function pic()
    {
        return $this->hasOne(SPKQualityPIC::class, 'spk_qualities_id');
    }

    public function details()
    {
        return $this->hasMany(SPKQualityDetail::class, 'spk_qualities_id');
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
