<?php

namespace App\Models\Quality\Defect;

use App\Models\Quality\Defect\Pivot\DefectStatusDetail;
use App\Models\Quality\Defect\Pivot\DefectStatusPIC;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DefectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'spk_marketing_id',
        'no_surat',
        'tipe_sumber',
        'sumber_id',
        'tipe',
        'volume',
        'serial_number',
        'file_upload',
        'note',
        'status_penyelesaian',
    ];

    public function getSumberAttribute()
    {
        return match ($this->tipe_sumber) {
            'electrical' => PengecekanMaterialElectrical::find($this->sumber_id),
            'stainless_steel' => PengecekanMaterialSS::find($this->sumber_id),
            default => null,
        };
    }

    public function getNoSpkAttribute()
    {
        $sumber = $this->sumber;

        if (!$sumber) return '-';

        return $sumber->spk->no_spk ?? '-';
    }

    // public function spk()
    // {
    //     return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    // }

    public function details()
    {
        return $this->hasMany(DefectStatusDetail::class, 'defect_status_id');
    }

    public function pic()
    {
        return $this->hasOne(DefectStatusPIC::class);
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

        static::updating(function ($model) {
            if (
                $model->isDirty('file_upload') &&
                $model->getOriginal('file_upload') &&
                Storage::disk('public')->exists($model->getOriginal('file_upload'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('file_upload'));
            }
        });

        static::deleting(function ($model) {

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->file_upload && Storage::disk('public')->exists($model->file_upload)) {
                Storage::disk('public')->delete($model->file_upload);
            }
        });
    }
}
