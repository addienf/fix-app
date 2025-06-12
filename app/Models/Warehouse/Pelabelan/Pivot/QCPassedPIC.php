<?php

namespace App\Models\Warehouse\Pelabelan\Pivot;

use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class QCPassedPIC extends Model
{
    use HasFactory;

    protected $table = 'qc_passed_pics';

    protected $fillable = [
        'qc_passed_id',
        'created_signature',
        'created_name',
        'approved_signature',
        'approved_name',
    ];

    public function qc()
    {
        return $this->belongsTo(QCPassed::class, 'qc_passed_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('created_signature') &&
                $model->getOriginal('created_signature') &&
                Storage::disk('public')->exists($model->getOriginal('created_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('created_signature'));
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
            if ($model->created_signature && Storage::disk('public')->exists($model->created_signature)) {
                Storage::disk('public')->delete($model->created_signature);
            }

            if ($model->approved_signature && Storage::disk('public')->exists($model->approved_signature)) {
                Storage::disk('public')->delete($model->approved_signature);
            }
        });
    }
}
