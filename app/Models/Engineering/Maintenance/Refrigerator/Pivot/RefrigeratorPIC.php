<?php

namespace App\Models\Engineering\Maintenance\Refrigerator\Pivot;

use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RefrigeratorPIC extends Model
{
    use HasFactory;

    protected $table = 'refrigerator_pics';

    protected $fillable = [
        'refrigerator_id',
        'checked_name',
        'checked_signature',
        'checked_date',
        'approved_name',
        'approved_signature',
        'approved_date',
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class, 'refrigerator_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_name');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_name');
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
