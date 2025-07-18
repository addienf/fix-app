<?php

namespace App\Models\Quality\KelengkapanMaterial\SS\Pivot;

use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KelengkapanMaterialSSPIC extends Model
{
    use HasFactory;

    protected $table = 'kelengkapan_material_ss_pics';

    protected $fillable = [
        'kelengkapan_material_id',
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
        return $this->belongsTo(KelengkapanMaterialSS::class, 'kelengkapan_material_id');
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
