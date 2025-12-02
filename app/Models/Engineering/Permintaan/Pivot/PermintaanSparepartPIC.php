<?php

namespace App\Models\Engineering\Permintaan\Pivot;

use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanSparepartPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_sparepart_pics';

    protected $fillable = [
        'sparepart_id',
        'dibuat_signature',
        'dibuat_name',
        'diketahui_signature',
        'diketahui_name',
        'diserahkan_signature',
        'diserahkan_name',
    ];

    public function permintaanSparepart()
    {
        return $this->belongsTo(PermintaanSparepart::class, 'sparepart_id');
    }

    public function dibuatName()
    {
        return $this->belongsTo(User::class, 'dibuat_name');
    }

    public function diketahuiName()
    {
        return $this->belongsTo(User::class, 'diketahui_name');
    }

    public function diserahkanName()
    {
        return $this->belongsTo(User::class, 'diserahkan_name');
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
