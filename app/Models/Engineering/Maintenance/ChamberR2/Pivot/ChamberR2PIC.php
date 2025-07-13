<?php

namespace App\Models\Engineering\Maintenance\ChamberR2\Pivot;

use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChamberR2PIC extends Model
{
    use HasFactory;

    protected $table = 'chamber_r2_pics';

    protected $fillable = [
        'r2_id',
        'checked_name',
        'checked_signature',
        'checked_date',
        'approved_name',
        'approved_signature',
        'approved_date',
    ];

    public function chamberR2()
    {
        return $this->belongsTo(ChamberR2::class, 'r2_id');
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

            if ($model->approved_signature && Storage::disk('public')->exists($model->approved_signature)) {
                Storage::disk('public')->delete($model->approved_signature);
            }
        });
    }
}
