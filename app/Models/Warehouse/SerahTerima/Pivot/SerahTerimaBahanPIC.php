<?php

namespace App\Models\Warehouse\SerahTerima\Pivot;

use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SerahTerimaBahanPIC extends Model
{
    use HasFactory;
    protected $table = 'serah_terima_bahan_pics';

    protected $fillable = [
        'serah_terima_bahan_id',
        'submit_signature',
        'submit_name',
        'receive_signature',
        'receive_name',
    ];

    public function serahTerima()
    {
        return $this->belongsTo(SerahTerimaBahan::class);
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
        });

        static::deleting(function ($model) {
            if ($model->submit_signature && Storage::disk('public')->exists($model->submit_signature)) {
                Storage::disk('public')->delete($model->submit_signature);
            }

            if ($model->receive_signature && Storage::disk('public')->exists($model->receive_signature)) {
                Storage::disk('public')->delete($model->receive_signature);
            }
        });
    }
}
