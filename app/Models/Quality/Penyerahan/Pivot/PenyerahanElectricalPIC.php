<?php

namespace App\Models\Quality\Penyerahan\Pivot;

use App\Models\Quality\Penyerahan\PenyerahanElectrical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PenyerahanElectricalPIC extends Model
{
    use HasFactory;

    protected $table = 'penyerahan_electrical_pics';

    protected $fillable = [
        'penyerahan_electrical_id',
        'submit_signature',
        'submit_name',
        'receive_signature',
        'receive_name',
        'knowing_signature',
        'knowing_name',
    ];

    public function penyerahanElectrical()
    {
        return $this->belongsTo(PenyerahanElectrical::class, 'penyerahan_electrical_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('submit_signature') &&
                $model->getOriginal('submit_signature') &&
                Storage::disk('public')->exists($model->getOriginal('submit_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('submit_signature'));
            }

            if (
                $model->isDirty('receive_signature') &&
                $model->getOriginal('receive_signature') &&
                Storage::disk('public')->exists($model->getOriginal('receive_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('receive_signature'));
            }

            if (
                $model->isDirty('receive_signature') &&
                $model->getOriginal('receive_signature') &&
                Storage::disk('public')->exists($model->getOriginal('receive_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('receive_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->submit_signature && Storage::disk('public')->exists($model->submit_signature)) {
                Storage::disk('public')->delete($model->submit_signature);
            }

            if ($model->receive_signature && Storage::disk('public')->exists($model->receive_signature)) {
                Storage::disk('public')->delete($model->receive_signature);
            }

            if ($model->receive_signature && Storage::disk('public')->exists($model->receive_signature)) {
                Storage::disk('public')->delete($model->receive_signature);
            }
        });
    }
}
