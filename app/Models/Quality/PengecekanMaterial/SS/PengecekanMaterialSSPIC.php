<?php

namespace App\Models\Quality\PengecekanMaterial\SS;

use App\Models\User;
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

    public function inspectedName()
    {
        return $this->belongsTo(User::class, 'inspected_name');
    }

    public function acceptedName()
    {
        return $this->belongsTo(User::class, 'accepted_name');
    }

    public function approvedName()
    {
        return $this->belongsTo(User::class, 'approved_name');
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
