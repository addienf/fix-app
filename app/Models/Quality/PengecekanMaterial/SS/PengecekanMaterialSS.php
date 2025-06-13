<?php

namespace App\Models\Quality\PengecekanMaterial\SS;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanMaterialSS extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_material_ss';

    protected $fillable = [
        'spk_marketing_id',
        'tipe',
        'ref_document',
        'note',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function pic()
    {
        return $this->hasOne(PengecekanMaterialSSPIC::class, 'pengecekan_material_id');
    }

    public function detail()
    {
        return $this->hasOne(PengecekanMaterialSSDetail::class, 'pengecekan_material_id');
    }

    public function penyerahan()
    {
        return $this->hasOne(PenyerahanElectrical::class, 'pengecekan_material_id');
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->detail) {
                $model->detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->identitas) {
                $model->identitas->delete();
            }
        });
    }
}
