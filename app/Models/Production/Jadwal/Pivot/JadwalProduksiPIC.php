<?php

namespace App\Models\Production\Jadwal\Pivot;

use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JadwalProduksiPIC extends Model
{
    use HasFactory;

    protected $table = 'jadwal_produksi_pics';

    protected $fillable = [
        'jadwal_produksi_id',
        'create_signature',
        'create_name',
        'approve_signature',
        'approve_name',
    ];

    public function jadwalProduksi()
    {
        return $this->belongsTo(JadwalJadwalProduksi::class);
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
                $model->isDirty('approve_signature') &&
                $model->getOriginal('approve_signature') &&
                Storage::disk('public')->exists($model->getOriginal('approve_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('approve_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->create_signature && Storage::disk('public')->exists($model->create_signature)) {
                Storage::disk('public')->delete($model->create_signature);
            }

            if ($model->approve_signature && Storage::disk('public')->exists($model->approve_signature)) {
                Storage::disk('public')->delete($model->approve_signature);
            }
        });
    }
}
