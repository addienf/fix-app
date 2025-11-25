<?php

namespace App\Models\Engineering\Pelayanan\Pivot;

use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanPelayananPelangganPIC extends Model
{
    use HasFactory;

    protected $table = 'pelayanan_pelanggan_pics';

    protected $fillable = [
        'pelayanan_id',
        'diketahui_signature',
        'diketahui_name',
        'diterima_signature',
        'diterima_name',
        'dibuat_signature',
        'dibuat_name',
    ];

    public function pelayananPelanggan()
    {
        return $this->belongsTo(PermintaanPelayananPelanggan::class, 'pelayanan_id');
    }

    public function diketahuiName()
    {
        return $this->belongsTo(User::class, 'diketahui_name');
    }

    public function diterimaName()
    {
        return $this->belongsTo(User::class, 'diterima_name');
    }

    public function dibuatName()
    {
        return $this->belongsTo(User::class, 'dibuat_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('diketahui_signature') &&
                $model->getOriginal('diketahui_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diketahui_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diketahui_signature'));
            }

            if (
                $model->isDirty('diterima_signature') &&
                $model->getOriginal('diterima_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diterima_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diterima_signature'));
            }

            if (
                $model->isDirty('dibuat_signature') &&
                $model->getOriginal('dibuat_signature') &&
                Storage::disk('public')->exists($model->getOriginal('dibuat_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('dibuat_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->diketahui_signature && Storage::disk('public')->exists($model->diketahui_signature)) {
                Storage::disk('public')->delete($model->diketahui_signature);
            }

            if ($model->diterima_signature && Storage::disk('public')->exists($model->diterima_signature)) {
                Storage::disk('public')->delete($model->diterima_signature);
            }

            if ($model->dibuat_signature && Storage::disk('public')->exists($model->dibuat_signature)) {
                Storage::disk('public')->delete($model->dibuat_signature);
            }
        });
    }
}
