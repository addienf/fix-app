<?php

namespace App\Models\Quality\Defect\Pivot;

use App\Models\Quality\Defect\DefectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;

class DefectStatusDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'defect_status_id',
        'spesifikasi_ditolak',
        'spesifikasi_revisi',
    ];

    protected $casts = [
        'spesifikasi_ditolak' => 'array',
        'spesifikasi_revisi' => 'array',
    ];

    public function defect()
    {
        return $this->belongsTo(DefectStatus::class, 'defect_status_id');
    }

    protected static function booted()
    {
        // static::saved(function ($model) {

        //     $defect = $model->defect;

        //     if (!$defect) {
        //         return;
        //     }

        //     $spesifikasiRevisi = $model->spesifikasi_revisi;

        //     $sumber = match ($defect->tipe_sumber) {
        //         'electrical' => PengecekanMaterialElectrical::find($defect->sumber_id),
        //         'stainless_steel' => PengecekanMaterialSS::find($defect->sumber_id),
        //         default => null,
        //     };

        //     if (!$sumber || !$sumber->detail) return;

        //     $detailModel = $sumber->detail;
        //     if (!$detailModel) return;

        //     $detailsLama = collect($detailModel->details ?? []);
        //     $revisiBaru = collect($spesifikasiRevisi);

        //     $mainPartsBaru = $revisiBaru->pluck('mainPart');

        //     $filteredLama = $detailsLama->reject(
        //         fn($item) =>
        //         in_array($item['mainPart'], $mainPartsBaru->all())
        //     );

        //     $merged = $filteredLama->merge($revisiBaru)->values();

        //     // dd($merged);
        //     $detailModel->update([
        //         'details' => $merged->toArray(),
        //     ]);
        // });
        static::saved(function ($model) {
            $defect = $model->defect;

            if (!$defect) return;

            $sumber = match ($defect->tipe_sumber) {
                'electrical' => PengecekanMaterialElectrical::find($defect->sumber_id),
                'stainless_steel' => PengecekanMaterialSS::find($defect->sumber_id),
                default => null,
            };

            if (!$sumber || !$sumber->detail) return;

            $detailModel = $sumber->detail;
            $detailsLama = collect($detailModel->details ?? []);

            $revisiBaru = collect($model->spesifikasi_revisi ?? [])
                ->keyBy(fn($item) => $item['mainPart'] ?? null);

            $detailsFinal = $detailsLama->map(function ($item) use ($revisiBaru) {
                $mainPart = $item['mainPart'] ?? null;

                if ($mainPart && $revisiBaru->has($mainPart)) {
                    return $revisiBaru->get($mainPart);
                }

                return $item;
            });

            $detailModel->update([
                'details' => $detailsFinal->toArray(),
            ]);
        });
    }
}
