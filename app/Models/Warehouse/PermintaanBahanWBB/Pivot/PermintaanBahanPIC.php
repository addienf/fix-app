<?php

namespace App\Models\Warehouse\PermintaanBahanWBB\Pivot;

use App\Models\User;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PermintaanBahanPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_bahan_pics';

    protected $fillable = [
        'permintaan_bahan_wbb_id',
        'create_signature',
        'create_name',
    ];

    public function permintaanBahanWBB()
    {
        return $this->belongsTo(PermintaanBahan::class, 'permintaan_bahan_wbb_id');
    }

    public function createName()
    {
        return $this->belongsTo(User::class, 'create_name');
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
        });

        static::deleting(function ($model) {
            if ($model->create_signature && Storage::disk('public')->exists($model->create_signature)) {
                Storage::disk('public')->delete($model->create_signature);
            }
        });
    }
}
