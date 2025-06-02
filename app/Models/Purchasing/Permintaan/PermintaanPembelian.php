<?php

namespace App\Models\Purchasing\Permintaan;

use App\Models\Purchasing\Permintaan\Pivot\PermintaanPembelianDetail;
use App\Models\Purchasing\Permintaan\Pivot\PermintaanPembelianPIC;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_bahan_wbb_id',
    ];


    public function permintaanBahanWBB()
    {
        return $this->belongsTo(PermintaanBahan::class, 'permintaan_bahan_wbb_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanPembelianDetail::class);
    }

    public function pic()
    {
        return $this->hasOne(PermintaanPembelianPIC::class);
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
