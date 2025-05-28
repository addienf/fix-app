<?php

namespace App\Models\Sales\SpesifikasiProducts\Pivot;

use App\Models\General\Product;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpesifikasiProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'spesifikasi_produk_id',
        'product_id',
        'quantity',
        'specification'
    ];

    protected $casts = [
        'specification' => 'array',
    ];

    public function spesifikasiProduk()
    {
        return $this->belongsTo(SpesifikasiProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function file()
    {
        return $this->hasOne(SpesifikasiProductFiles::class);
    }
    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->file) {
                $model->file->delete();
            }
        });
    }
}
