<?php

namespace App\Models\Engineering\Maintenance\Refrigerator\Pivot;

use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\User;
use App\Traits\HasSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RefrigeratorPIC extends Model
{
    use HasFactory, HasSignature;

    protected $table = 'refrigerator_pics';

    // protected $fillable = [
    //     'refrigerator_id',
    //     'checked_name',
    //     'checked_signature',
    //     'checked_date',
    //     'approved_name',
    //     'approved_signature',
    //     'approved_date',
    // ];

    protected $fillable = [
        'refrigerator_id',
        'checked_name',
        'checked_signature',
        'checked_date',
        'approved_name',
        'approved_signature',
        'approved_date',
        'sign_token',
        'sign_token_expires_at',
        'signed_at',
        'signed_ip',
    ];

    public function signatureConfig(): array
    {
        return [
            'name_field' => 'approved_name',
            'signature_field' => 'approved_signature',
            'date_field' => 'approved_date',
            'upload_path' => 'Engineering/Maintenance/Refrigerator/Signature',
        ];
    }

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
