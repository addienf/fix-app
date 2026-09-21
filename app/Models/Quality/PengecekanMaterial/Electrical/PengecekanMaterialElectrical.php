<?php

namespace App\Models\Quality\PengecekanMaterial\Electrical;

use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\PengecekanMaterial\Electrical\Pivot\PengecekanMaterialElectricalDetail;
use App\Models\Quality\PengecekanMaterial\Electrical\Pivot\PengecekanMaterialElectricalPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanMaterialElectrical extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_electrical';

    protected $fillable = [
        'spk_qualities_id',
        'tipe',
        'volume',
        'note',
        'status_penyelesaian',
    ];

    public function defectStatus()
    {
        return $this->hasOne(DefectStatus::class, 'sumber_id')
            ->where('tipe_sumber', 'electrical');
    }

    public function spkQC()
    {
        return $this->belongsTo(SPKQuality::class, 'spk_qualities_id');
    }

    public function penyerahanProdukJadi()
    {
        return $this->hasOne(PenyerahanProdukJadi::class, 'pengecekan_electrical_id');
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
        static::saving(function ($model) {
            if (
                $model->pic?->accepted_signature &&
                $model->status_penyelesaian !== 'Diterima'
            ) {
                $model->status_penyelesaian = 'Diterima';
            }

            if (
                $model->pic?->approved_signature &&
                $model->status_penyelesaian !== 'Disetujui'
            ) {
                $model->status_penyelesaian = 'Disetujui';
            }
        });

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
