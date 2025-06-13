<?php

namespace App\Models\Warehouse\Incomming\Pivot;

use App\Models\Warehouse\Incomming\IncommingMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IncommingMaterialPIC extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_pics';

    protected $fillable = [
        'material_non_ss_id',
        'submited_signature',
        'submited_name',
        'received_signature',
        'received_name',

    ];

    public function incommingMaterial()
    {
        return $this->belongsTo(IncommingMaterial::class, 'incomming_material_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('submited_signature') &&
                $model->getOriginal('submited_signature') &&
                Storage::disk('public')->exists($model->getOriginal('submited_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('submited_signature'));
            }

            if (
                $model->isDirty('received_signature') &&
                $model->getOriginal('received_signature') &&
                Storage::disk('public')->exists($model->getOriginal('received_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('received_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->submited_signature && Storage::disk('public')->exists($model->submited_signature)) {
                Storage::disk('public')->delete($model->submited_signature);
            }

            if ($model->received_signature && Storage::disk('public')->exists($model->received_signature)) {
                Storage::disk('public')->delete($model->received_signature);
            }
        });
    }
}
