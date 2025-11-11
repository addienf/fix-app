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
        'dikonfirmasi_signature',
        'dikonfirmasi_name',
        'diketahui_signature',
        'diketahui_name',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function dikonfirmasiNama()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_name');
    }

    public function diketahuiNama()
    {
        return $this->belongsTo(User::class, 'diketahui_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('dikonfirmasi_signature') &&
                $model->getOriginal('dikonfirmasi_signature') &&
                Storage::disk('public')->exists($model->getOriginal('dikonfirmasi_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('dikonfirmasi_signature'));
            }

            if (
                $model->isDirty('diketahui_signature') &&
                $model->getOriginal('diketahui_signature') &&
                Storage::disk('public')->exists($model->getOriginal('diketahui_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('diketahui_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->dikonfirmasi_signature && Storage::disk('public')->exists($model->dikonfirmasi_signature)) {
                Storage::disk('public')->delete($model->dikonfirmasi_signature);
            }

            if ($model->diketahui_signature && Storage::disk('public')->exists($model->diketahui_signature)) {
                Storage::disk('public')->delete($model->diketahui_signature);
            }
        });
    }
}
