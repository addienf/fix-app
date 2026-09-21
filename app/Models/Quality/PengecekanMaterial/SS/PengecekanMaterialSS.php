<?php

namespace App\Models\Quality\PengecekanMaterial\SS;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Quality\Defect\DefectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanMaterialSS extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_material_ss';

    protected $fillable = [
        'spk_qualities_id',
        'tipe',
        'ref_document',
        'note',
        'status_penyelesaian',
    ];

    public function defectStatus()
    {
        return $this->hasOne(DefectStatus::class, 'sumber_id')
            ->where('tipe_sumber', 'stainless_steel');
    }

    public function spkQC()
    {
        return $this->belongsTo(SPKQuality::class, 'spk_qualities_id');
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
