<?php

namespace App\Models\Production\PermintaanBahanProduksi\Pivot;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanAlatDanBahanPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_alat_dan_bahan_pics';

    protected $fillable = [
        'permintaan_bahan_id',
        'dibuat_signature',
        'dibuat_name',
        'diketahui_signature',
        'diketahui_name',
        'diserahkan_signature',
        'diserahkan_name',
    ];

    public function permintaanBahan()
    {
        return $this->belongsTo(PermintaanAlatDanBahan::class, 'permintaan_bahan_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('dibuat_signature') &&
                $model->getOriginal('dibuat_signature') &&
                Storage::disk('public')->exists($model->getOriginal('dibuat_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('dibuat_signature'));
            }

            if (
                $model->isDirty('diketahui_signature') &&
                $model->getOriginal('diketahui_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diketahui_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diketahui_signature'));
            }

            if (
                $model->isDirty('diserahkan_signature') &&
                $model->getOriginal('diserahkan_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diserahkan_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diserahkan_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->dibuat_signature && Storage::disk('public')->exists($model->dibuat_signature)) {
                Storage::disk('public')->delete($model->dibuat_signature);
            }

            if ($model->diketahui_signature && Storage::disk('public')->exists($model->diketahui_signature)) {
                Storage::disk('public')->delete($model->diketahui_signature);
            }

            if ($model->diserahkan_signature && Storage::disk('public')->exists($model->diserahkan_signature)) {
                Storage::disk('public')->delete($model->diserahkan_signature);
            }
        });
    }
}
