<?php

namespace App\Models\Engineering\SPK\SPKService\Pivot;

use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Petugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'nama_teknisi',
        'jabatan',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('ttd') &&
                $model->getOriginal('ttd') &&
                Storage::disk('public')->exists($model->getOriginal('ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('ttd'));
            }
        });

        static::deleting(function ($model) {
            if ($model->ttd && Storage::disk('public')->exists($model->ttd)) {
                Storage::disk('public')->delete($model->ttd);
            }
        });
    }
}
