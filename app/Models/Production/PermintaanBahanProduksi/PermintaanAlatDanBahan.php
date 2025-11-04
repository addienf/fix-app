<?php

namespace App\Models\Production\PermintaanBahanProduksi;

use App\Models\Production\PermintaanBahanProduksi\Pivot\PermintaanAlatDanBahanDetail;
use App\Models\Production\PermintaanBahanProduksi\Pivot\PermintaanAlatDanBahanPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $id
 * @property string|null $spk_marketing_id
 * @property string|null $tanggal
 * @property string|null $no_surat
 * @property string|null $dari
 * @property string|null $kepada
 * @property string|null $status
 * @property string|null $status_penyerahan
 */
class PermintaanAlatDanBahan extends Model
{
    use HasFactory, HasCacheManager;

    protected $fillable = [
        'spk_marketing_id',
        'tanggal',
        'no_surat',
        'dari',
        'kepada',
        'status',
        'status_penyerahan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanAlatDanBahanDetail::class, 'permintaan_bahan_id');
    }

    public function pic()
    {
        return $this->hasOne(PermintaanAlatDanBahanPIC::class, 'permintaan_bahan_id');
    }

    public function permintaanBahanWBB()
    {
        return $this->hasOne(PermintaanBahan::class, 'permintaan_bahan_pro_id');
    }

    public function serahTerimaBahan()
    {
        return $this->hasOne(SerahTerimaBahan::class, 'permintaan_bahan_pro_id');
    }

    public static array $CACHE_KEYS = [
        'permintaanBahanWBB' => 'permintaan_produksi_warehouse',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->diketahui_signature &&
                $model->status_penyerahan !== 'Diketahui'
            ) {
                $model->status_penyerahan = 'Diketahui';
            }

            if (
                $model->pic?->diserahkan_signature &&
                $model->status_penyerahan !== 'Diserahkan'
            ) {
                $model->status_penyerahan = 'Diserahkan';
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->permintaanBahanWBB) {
                $model->permintaanBahanWBB->delete();
            }
        });

        static::saved(function () {
            SPKMarketing::clearModelCaches();
        });

        static::deleted(function () {
            SPKMarketing::clearModelCaches();
        });
    }
}
