<?php

namespace App\Models\Sales\SpesifikasiProducts\Pivot;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;

class SpesifikasiProductPIC extends Model
{
    use HasFactory;

    protected $table = 'spesifikasi_product_pics';

    protected $fillable = [
        'spesifikasi_product_id',
        'signed_signature',
        'signed_name',
        'signed_date',
        'accepted_signature',
        'accepted_name',
        'accepted_date',
        'acknowledge_signature',
        'acknowledge_name',
        'acknowledge_date',
    ];

    public function spesifikasiProduk()
    {
        return $this->belongsTo(SpesifikasiProduct::class);
    }

    public function signedName()
    {
        return $this->belongsTo(User::class, 'signed_name');
    }

    public function acceptedName()
    {
        return $this->belongsTo(User::class, 'accepted_name');
    }

    public function acknowledgeName()
    {
        return $this->belongsTo(User::class, 'acknowledge_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('signed_signature') &&
                $model->getOriginal('signed_signature') &&
                Storage::disk('public')->exists($model->getOriginal('signed_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('signed_signature'));
            }

            if (
                $model->isDirty('accepted_signature') &&
                $model->getOriginal('accepted_signature') &&
                Storage::disk('public')->exists($model->getOriginal('accepted_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('accepted_signature'));
            }

            if (
                $model->isDirty('acknowledge_signature') &&
                $model->getOriginal('acknowledge_signature') &&
                Storage::disk('public')->exists($model->getOriginal('acknowledge_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('acknowledge_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->signed_signature && Storage::disk('public')->exists($model->signed_signature)) {
                Storage::disk('public')->delete($model->signed_signature);
            }

            if ($model->accepted_signature && Storage::disk('public')->exists($model->accepted_signature)) {
                Storage::disk('public')->delete($model->accepted_signature);
            }

            if ($model->acknowledge_signature && Storage::disk('public')->exists($model->acknowledge_signature)) {
                Storage::disk('public')->delete($model->acknowledge_signature);
            }
        });
    }
}
