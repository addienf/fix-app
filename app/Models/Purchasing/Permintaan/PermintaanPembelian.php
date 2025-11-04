<?php

namespace App\Models\Purchasing\Permintaan;

use App\Models\Purchasing\Permintaan\Pivot\PermintaanPembelianDetail;
use App\Models\Purchasing\Permintaan\Pivot\PermintaanPembelianPIC;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    use HasFactory, HasCacheManager;

    protected $fillable = [
        'permintaan_bahan_wbb_id',
        'status_persetujuan',
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

    public function materialNonSS()
    {
        return $this->hasOne(IncommingMaterialNonSS::class);
    }

    public function materialSS()
    {
        return $this->hasOne(IncommingMaterialSS::class);
    }

    public function incommingMaterial()
    {
        return $this->hasOne(IncommingMaterial::class);
    }

    public static array $CACHE_KEYS = [
        'materialNonSS' => 'permintaan_pembelian_material_non_ss',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->knowing_signature &&
                $model->status_persetujuan !== 'Disetujui'
            ) {
                $model->status_persetujuan = 'Disetujui';
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });

        static::saved(function () {
            PermintaanBahan::clearModelCaches();
        });

        static::deleted(function () {
            PermintaanBahan::clearModelCaches();
        });
    }
}
