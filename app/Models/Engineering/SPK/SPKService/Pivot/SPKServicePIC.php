<?php

namespace App\Models\Engineering\SPK\SPKService\Pivot;

use App\Models\Engineering\SPK\SPKService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SPKServicePIC extends Model
{
    use HasFactory;

    protected $table = 'spk_service_pics';

    protected $fillable = [
        'spk_service_id',
        'dikonfirmasi_ttd',
        'dikonfirmasi_nama',
        'diketahui_ttd',
        'diketahui_nama',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function dikonfirmasiNama()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_nama');
    }

    public function diketahuiNama()
    {
        return $this->belongsTo(User::class, 'diketahui_nama');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('dikonfirmasi_ttd') &&
                $model->getOriginal('dikonfirmasi_ttd') &&
                Storage::disk('public')->exists($model->getOriginal('dikonfirmasi_ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('dikonfirmasi_ttd'));
            }

            if (
                $model->isDirty('diketahui_ttd') &&
                $model->getOriginal('diketahui_ttd') &&
                Storage::disk('public')->exists($model->getOriginal('diketahui_ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diketahui_ttd'));
            }
        });

        static::deleting(function ($model) {
            if ($model->dikonfirmasi_ttd && Storage::disk('public')->exists($model->dikonfirmasi_ttd)) {
                Storage::disk('public')->delete($model->dikonfirmasi_ttd);
            }

            if ($model->diketahui_ttd && Storage::disk('public')->exists($model->diketahui_ttd)) {
                Storage::disk('public')->delete($model->diketahui_ttd);
            }
        });
    }
}
