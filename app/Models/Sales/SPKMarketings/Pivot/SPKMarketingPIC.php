<?php

namespace App\Models\Sales\SPKMarketings\Pivot;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SPKMarketingPIC extends Model
{
    use HasFactory;
    protected $table = 'spk_marketing_pics';

    protected $fillable = [
        'spk_marketing_id',
        'create_signature',
        'create_name',
        'receive_signature',
        'receive_name',
    ];

    public function spkMarketing()
    {
        return $this->belongsTo(SPKMarketing::class);
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
                $model->isDirty('receive_signature') &&
                $model->getOriginal('receive_signature') &&
                Storage::disk('public')->exists($model->getOriginal('receive_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('receive_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->create_signature && Storage::disk('public')->exists($model->create_signature)) {
                Storage::disk('public')->delete($model->create_signature);
            }

            if ($model->receive_signature && Storage::disk('public')->exists($model->receive_signature)) {
                Storage::disk('public')->delete($model->receive_signature);
            }
        });
    }
}
