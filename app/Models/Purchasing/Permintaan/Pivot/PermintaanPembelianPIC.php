<?php

namespace App\Models\Purchasing\Permintaan\Pivot;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanPembelianPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_pembelian_pics';

    protected $fillable = [
        'permintaan_pembelian_id',
        'create_signature',
        'create_name',
        'knowing_signature',
        'knowing_name',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class);
    }

    public function createName()
    {
        return $this->belongsTo(User::class, 'create_name');
    }

    public function knowingName()
    {
        return $this->belongsTo(User::class, 'knowing_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('create_signature') &&
                $model->getOriginal('create_signature') &&
                Storage::disk('public')->exists($model->getOriginal('create_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('create_signature'));
            }

            if (
                $model->isDirty('knowing_signature') &&
                $model->getOriginal('knowing_signature') &&
                Storage::disk('public')->exists($model->getOriginal('knowing_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('knowing_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->create_signature && Storage::disk('public')->exists($model->create_signature)) {
                Storage::disk('public')->delete($model->create_signature);
            }

            if ($model->knowing_signature && Storage::disk('public')->exists($model->knowing_signature)) {
                Storage::disk('public')->delete($model->knowing_signature);
            }
        });
    }
}
