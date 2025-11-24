<?php

namespace App\Models\Quality\Release;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductReleasePIC extends Model
{
    use HasFactory;

    protected $table = 'product_release_pics';

    protected $fillable = [
        'release_id',
        'dibuat_signature',
        'dibuat_name',
        'dibuat_date',
        'dikonfirmasi_signature',
        'dikonfirmasi_name',
        'dikonfirmasi_date',
        'diterima_signature',
        'diterima_name',
        'diterima_date',
        'diketahui_signature',
        'diketahui_name',
        'diketahui_date',
    ];

    public function productRelease()
    {
        return $this->belongsTo(ProductRelease::class, 'release_id');
    }

    public function dibuatName()
    {
        return $this->belongsTo(User::class, 'dibuat_name');
    }

    public function dikonfirmasiName()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_name');
    }

    public function diterimaName()
    {
        return $this->belongsTo(User::class, 'diterima_name');
    }

    public function diketahuiName()
    {
        return $this->belongsTo(User::class, 'diketahui_name');
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
                $model->isDirty('dikonfirmasi_signature') &&
                $model->getOriginal('dikonfirmasi_signature') &&
                Storage::disk('public')->exists($model->getOriginal('dikonfirmasi_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('dikonfirmasi_signature'));
            }

            if (
                $model->isDirty('diterima_signature') &&
                $model->getOriginal('diterima_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diterima_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diterima_signature'));
            }

            if (
                $model->isDirty('diketahui_signature') &&
                $model->getOriginal('diketahui_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diketahui_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diketahui_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->dibuat_signature && Storage::disk('public')->exists($model->dibuat_signature)) {
                Storage::disk('public')->delete($model->dibuat_signature);
            }

            if ($model->dikonfirmasi_signature && Storage::disk('public')->exists($model->dikonfirmasi_signature)) {
                Storage::disk('public')->delete($model->dikonfirmasi_signature);
            }

            if ($model->diterima_signature && Storage::disk('public')->exists($model->diterima_signature)) {
                Storage::disk('public')->delete($model->diterima_signature);
            }

            if ($model->diketahui_signature && Storage::disk('public')->exists($model->diketahui_signature)) {
                Storage::disk('public')->delete($model->diketahui_signature);
            }
        });
    }
}
