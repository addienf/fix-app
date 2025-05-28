<?php

namespace App\Models\Sales\SpesifikasiProducts\Pivot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SpesifikasiProductFiles extends Model
{
    use HasFactory;

    protected $fillable = ['spesifikasi_product_detail_id', 'file_path'];

    public function spesifikasiProductDetail()
    {
        return $this->belongsTo(SpesifikasiProductDetail::class);
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('file_path') &&
                $model->getOriginal('file_path') &&
                Storage::disk('public')->exists($model->getOriginal('file_path'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('file_path'));
            }
        });

        static::deleting(function ($model) {
            if ($model->file_path && Storage::disk('public')->exists($model->file_path)) {
                Storage::disk('public')->delete($model->file_path);
            }
        });
    }
}
