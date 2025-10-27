<?php

namespace App\Models\Warehouse\PermintaanBahanWBB\Pivot;

use App\Models\User;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanBahanPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_bahan_pics';

    protected $fillable = [
        'permintaan_bahan_wbb_id',
        'dibuat_signature',
        'dibuat_name',
        'dibuat_date',
        'mengetahui_signature',
        'mengetahui_name',
        'mengetahui_date',
        'diserahkan_signature',
        'diserahkan_name',
        'diserahkan_date',
    ];

    public function permintaanBahanWBB()
    {
        return $this->belongsTo(PermintaanBahan::class, 'permintaan_bahan_wbb_id');
    }

    public function dibuatName()
    {
        return $this->belongsTo(User::class, 'dibuat_name');
    }

    public function mengetahuiName()
    {
        return $this->belongsTo(User::class, 'mengetahui_name');
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
                $model->isDirty('mengetahui_signature') &&
                $model->getOriginal('mengetahui_signature') &&
                Storage::disk('public')->exists($model->getOriginal('mengetahui_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('mengetahui_signature'));
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

            if ($model->mengetahui_signature && Storage::disk('public')->exists($model->mengetahui_signature)) {
                Storage::disk('public')->delete($model->mengetahui_signature);
            }

            if ($model->diserahkan_signature && Storage::disk('public')->exists($model->diserahkan_signature)) {
                Storage::disk('public')->delete($model->diserahkan_signature);
            }
        });
    }
}
