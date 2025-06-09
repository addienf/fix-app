<?php

namespace App\Models\Quality\KelengkapanMaterial\SS;

use App\Models\Quality\KelengkapanMaterial\SS\Pivot\KelengkapanMaterialSSDetail;
use App\Models\Quality\KelengkapanMaterial\SS\Pivot\KelengkapanMaterialSSPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelengkapanMaterialSS extends Model
{
    use HasFactory;

    protected $table = 'kelengkapan_material_ss';

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
        return $this->hasOne(KelengkapanMaterialSSPIC::class, 'kelengkapan_material_id');
    }

    public function detail()
    {
        return $this->hasOne(KelengkapanMaterialSSDetail::class, 'kelengkapan_material_id');
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
