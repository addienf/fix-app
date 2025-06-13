<?php

namespace App\Models\Quality\IncommingMaterial\MaterialNonSS\Pivot;

use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IncommingMaterialNonSSPIC extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_non_ss_pics';

    protected $fillable = [
        'material_non_ss_id',
        'checked_signature',
        'checked_name',
        'checked_date',
        'accepted_signature',
        'accepted_name',
        'accepted_date',
        'approved_signature',
        'approved_name',
        'approved_date',
    ];

    public function productNonSS()
    {
        return $this->belongsTo(IncommingMaterialNonSS::class, 'material_non_ss_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('checked_signature') &&
                $model->getOriginal('checked_signature') &&
                Storage::disk('public')->exists($model->getOriginal('checked_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('checked_signature'));
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
            if ($model->checked_signature && Storage::disk('public')->exists($model->checked_signature)) {
                Storage::disk('public')->delete($model->checked_signature);
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
