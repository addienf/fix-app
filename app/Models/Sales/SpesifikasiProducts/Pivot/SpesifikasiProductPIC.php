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
        'signature',
        'name',
        'date'
    ];

    public function spesifikasiProduk()
    {
        return $this->belongsTo(SpesifikasiProduct::class);
    }

    public function userName()
    {
        return $this->belongsTo(User::class, 'name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('signature') &&
                $model->getOriginal('signature') &&
                Storage::disk('public')->exists($model->getOriginal('signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->signature && Storage::disk('public')->exists($model->signature)) {
                Storage::disk('public')->delete($model->signature);
            }
        });
    }
}
