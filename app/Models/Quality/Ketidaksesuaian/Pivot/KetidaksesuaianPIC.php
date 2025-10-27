<?php

namespace App\Models\Quality\Ketidaksesuaian\Pivot;

use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KetidaksesuaianPIC extends Model
{
    use HasFactory;

    protected $table = 'ketidaksesuaian_pics';

    protected $fillable = [
        'ketidaksesuaian_id',
        'pelapor_signature',
        'pelapor_name',
        'pelapor_date',
        'diterima_signature',
        'diterima_name',
        'diterima_date',
    ];

    public function ketidaksesuaian()
    {
        return $this->belongsTo(Ketidaksesuaian::class, 'ketidaksesuaian_id');
    }

    public function pelaporName()
    {
        return $this->belongsTo(User::class, 'pelapor_name');
    }

    public function diterimaName()
    {
        return $this->belongsTo(User::class, 'diterima_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('pelapor_signature') &&
                $model->getOriginal('pelapor_signature') &&
                Storage::disk('public')->exists($model->getOriginal('pelapor_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('pelapor_signature'));
            }

            if (
                $model->isDirty('diterima_signature') &&
                $model->getOriginal('diterima_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diterima_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diterima_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->pelapor_signature && Storage::disk('public')->exists($model->pelapor_signature)) {
                Storage::disk('public')->delete($model->pelapor_signature);
            }

            if ($model->diterima_signature && Storage::disk('public')->exists($model->diterima_signature)) {
                Storage::disk('public')->delete($model->diterima_signature);
            }
        });
    }
}
