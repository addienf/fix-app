<?php

namespace App\Models\Quality\Standarisasi\Pivot;

use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StandarisasiDrawingPIC extends Model
{
    use HasFactory;

    protected $table = 'standarisasi_drawing_pics';

    protected $fillable = [
        'jadwal_produksi_id',
        'create_signature',
        'create_name',
        'check_signature',
        'check_name',
    ];

    public function standarisasi()
    {
        return $this->belongsTo(StandarisasiDrawing::class, 'standarisasi_drawing_id');
    }

    public function createName()
    {
        return $this->belongsTo(User::class, 'create_name');
    }

    public function checkName()
    {
        return $this->belongsTo(User::class, 'check_name');
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
                $model->isDirty('check_signature') &&
                $model->getOriginal('check_signature') &&
                Storage::disk('public')->exists($model->getOriginal('check_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('check_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->create_signature && Storage::disk('public')->exists($model->create_signature)) {
                Storage::disk('public')->delete($model->create_signature);
            }

            if ($model->check_signature && Storage::disk('public')->exists($model->check_signature)) {
                Storage::disk('public')->delete($model->check_signature);
            }
        });
    }
}
