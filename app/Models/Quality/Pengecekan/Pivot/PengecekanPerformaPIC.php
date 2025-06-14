<?php

namespace App\Models\Quality\Pengecekan\Pivot;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PengecekanPerformaPIC extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_performa_pics';

    protected $fillable = [
        'pengecekan_performa_id',
        'inspected_signature',
        'inspected_name',
        'inspected_date',
        'accepted_signature',
        'accepted_name',
        'accepted_date',
        'approved_signature',
        'approved_name',
        'approved_date',
    ];


    public function pengecekanPerforma()
    {
        return $this->belongsTo(PengecekanPerforma::class, 'pengecekan_performa_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('inspected_signature') &&
                $model->getOriginal('inspected_signature') &&
                Storage::disk('public')->exists($model->getOriginal('inspected_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('inspected_signature'));
            }

            if (
                $model->isDirty('accepted_signature') &&
                $model->getOriginal('accepted_signature') &&
                Storage::disk('public')->exists($model->getOriginal('accepted_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('accepted_signature'));
            }

            if (
                $model->isDirty('approved_signature') &&
                $model->getOriginal('approved_signature') &&
                Storage::disk('public')->exists($model->getOriginal('approved_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('approved_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->inspected_signature && Storage::disk('public')->exists($model->inspected_signature)) {
                Storage::disk('public')->delete($model->inspected_signature);
            }

            if ($model->accepted_signature && Storage::disk('public')->exists($model->accepted_signature)) {
                Storage::disk('public')->delete($model->accepted_signature);
            }

            if ($model->approved_signature && Storage::disk('public')->exists($model->approved_signature)) {
                Storage::disk('public')->delete($model->approved_signature);
            }
        });
    }
}
