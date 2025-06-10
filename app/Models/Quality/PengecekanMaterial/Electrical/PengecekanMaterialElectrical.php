<?php

namespace App\Models\Quality\PengecekanMaterial\Electrical;

use App\Models\Quality\PengecekanMaterial\Electrical\Pivot\PengecekanMaterialElectricalDetail;
use App\Models\Quality\PengecekanMaterial\Electrical\Pivot\PengecekanMaterialElectricalPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanMaterialElectrical extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_electrical';

    protected $fillable = [
        'spk_marketing_id',
        'tipe',
        'volume',
        'note',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function pic()
    {
        return $this->hasOne(PengecekanMaterialElectricalPIC::class, 'pengecekan_electrical_id');
    }

    public function detail()
    {
        return $this->hasOne(PengecekanMaterialElectricalDetail::class, 'pengecekan_electrical_id');
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
