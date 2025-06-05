<?php

namespace App\Models\Quality\Standarisasi\Pivot;

use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StandarisasiDrawingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'standarisasi_drawing_id',
        'lampiran',
        'catatan'
    ];

    public function standarisasi()
    {
        return $this->belongsTo(StandarisasiDrawing::class, 'standarisasi_drawing_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('lampiran') &&
                $model->getOriginal('lampiran') &&
                Storage::disk('public')->exists($model->getOriginal('lampiran'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('lampiran'));
            }
        });

        static::deleting(function ($model) {
            if ($model->lampiran && Storage::disk('public')->exists($model->lampiran)) {
                Storage::disk('public')->delete($model->lampiran);
            }
        });
    }
}
