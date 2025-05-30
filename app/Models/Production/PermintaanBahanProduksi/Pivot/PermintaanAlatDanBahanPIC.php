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
        'create_signature',
        'create_name',
        'receive_signature',
        'receive_name',
    ];

    public function permintaanBahan()
    {
        return $this->belongsTo(PermintaanAlatDanBahan::class, 'permintaan_bahan_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('create_signature') &&
                $model->getOriginal('create_signature') &&
                Storage::disk('public')->exists($model->getOriginal('create_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('create_signature'));
            }

            if (
                $model->isDirty('receive_signature') &&
                $model->getOriginal('receive_signature') &&
                Storage::disk('public')->exists($model->getOriginal('receive_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('receive_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->create_signature && Storage::disk('public')->exists($model->create_signature)) {
                Storage::disk('public')->delete($model->create_signature);
            }

            if ($model->receive_signature && Storage::disk('public')->exists($model->receive_signature)) {
                Storage::disk('public')->delete($model->receive_signature);
            }
        });
    }
}
