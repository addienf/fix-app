<?php

namespace App\Models\Quality\PengecekanMaterial\SS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PengecekanMaterialSSPIC extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_material_ss_pics';

    protected $fillable = [
        'pengecekan_material_id',
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

    public function kelengpakanSS()
    {
        return $this->belongsTo(PengecekanMaterialSS::class, 'pengecekan_material_id');
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
