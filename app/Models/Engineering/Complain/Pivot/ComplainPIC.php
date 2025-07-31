<?php

namespace App\Models\Engineering\Complain\Pivot;

use App\Models\Engineering\Complain\Complain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ComplainPIC extends Model
{
    use HasFactory;

    protected $table = 'complain_pics';

    protected $fillable = [
        'complain_id',
        'reported_name',
        'reported_signature',
        'reported_date',
    ];

    public function complain()
    {
        return $this->belongsTo(Complain::class, 'complain_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('reported_signature') &&
                $model->getOriginal('reported_signature') &&
                Storage::disk('public')->exists($model->getOriginal('reported_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('reported_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->reported_signature && Storage::disk('public')->exists($model->reported_signature)) {
                Storage::disk('public')->delete($model->reported_signature);
            }
        });
    }
}
