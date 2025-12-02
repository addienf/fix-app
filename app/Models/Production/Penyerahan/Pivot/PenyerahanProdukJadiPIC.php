<?php

namespace App\Models\Production\Penyerahan\Pivot;

use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PenyerahanProdukJadiPIC extends Model
{
    use HasFactory;

    protected $table = 'penyerahan_produk_jadi_pics';

    protected $fillable = [
        'produk_jadi_id',
        'submit_signature',
        'submit_name',
        'receive_signature',
        'receive_name',
    ];

    public function produkJadi()
    {
        return $this->belongsTo(PenyerahanProdukJadi::class, 'produk_jadi_id');
    }

    public function submitName()
    {
        return $this->belongsTo(User::class, 'submit_name');
    }

    public function receiveName()
    {
        return $this->belongsTo(User::class, 'receive_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('submit_signature') &&
                $model->getOriginal('submit_signature') &&
                Storage::disk('public')->exists($model->getOriginal('submit_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('submit_signature'));
            }

            if (
                $model->isDirty('receive_signature') &&
                $model->getOriginal('receive_signature') &&
                Storage::disk('public')->exists($model->getOriginal('receive_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('receive_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->submit_signature && Storage::disk('public')->exists($model->submit_signature)) {
                Storage::disk('public')->delete($model->submit_signature);
            }

            if ($model->receive_signature && Storage::disk('public')->exists($model->receive_signature)) {
                Storage::disk('public')->delete($model->receive_signature);
            }
        });
    }
}
