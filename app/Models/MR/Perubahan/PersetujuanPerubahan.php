<?php

namespace App\Models\MR\Perubahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PersetujuanPerubahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perubahan_id',
        'persetujuan_pic',
        'alasan_penolakan_pic',
        'signature_pic',
        'signature_pic_date',
        'persetujuan_manajemen',
        'alasan_penolakan_manajemen',
        'signature_manajemen',
        'signature_manajemen_date',
    ];

    public function perubahanInformasi()
    {
        return $this->belongsTo(PerubahanInformasi::class, 'perubahan_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('signature_pic') &&
                $model->getOriginal('signature_pic') &&
                Storage::disk('public')->exists($model->getOriginal('signature_pic'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('signature_pic'));
            }

            if (
                $model->isDirty('signature_manajemen') &&
                $model->getOriginal('signature_manajemen') &&
                Storage::disk('public')->exists($model->getOriginal('signature_manajemen'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('signature_manajemen'));
            }
        });

        static::deleting(function ($model) {
            if ($model->signature_pic && Storage::disk('public')->exists($model->signature_pic)) {
                Storage::disk('public')->delete($model->signature_pic);
            }

            if ($model->signature_manajemen && Storage::disk('public')->exists($model->signature_manajemen)) {
                Storage::disk('public')->delete($model->signature_manajemen);
            }
        });
    }
}
