<?php

namespace App\Models\Engineering\Permintaan\Pivot;

use App\Models\Engineering\Permintaan\PermintaanSparepart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanSparepartPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_sparepart_pics';

    protected $fillable = [
        'sparepart_id',
        'dibuat_ttd',
        'dibuat_name',
        'diketahui_ttd',
        'diketahui_name',
        'diserahkan_ttd',
        'diserahkan_name',
    ];

    public function permintaanSparepart()
    {
        return $this->belongsTo(PermintaanSparepart::class, 'sparepart_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('dibuat_ttd') &&
                $model->getOriginal('dibuat_ttd') &&
                Storage::disk('public')->exists($model->getOriginal('dibuat_ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('dibuat_ttd'));
            }

            if (
                $model->isDirty('diketahui_ttd') &&
                $model->getOriginal('diketahui_ttd') &&
                Storage::disk('public')->exists($model->getOriginal('diketahui_ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diketahui_ttd'));
            }

            if (
                $model->isDirty('diserahkan_ttd') &&
                $model->getOriginal('diserahkan_ttd') &&
                Storage::disk('public')->exists($model->getOriginal('diserahkan_ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diserahkan_ttd'));
            }
        });

        static::deleting(function ($model) {
            if ($model->dibuat_ttd && Storage::disk('public')->exists($model->dibuat_ttd)) {
                Storage::disk('public')->delete($model->dibuat_ttd);
            }

            if ($model->diketahui_ttd && Storage::disk('public')->exists($model->diketahui_ttd)) {
                Storage::disk('public')->delete($model->diketahui_ttd);
            }

            if ($model->diserahkan_ttd && Storage::disk('public')->exists($model->diserahkan_ttd)) {
                Storage::disk('public')->delete($model->diserahkan_ttd);
            }
        });
    }
}
