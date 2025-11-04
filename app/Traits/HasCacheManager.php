<?php

namespace App\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Support\Facades\Cache;

trait HasCacheManager
{
    public static function clearModelCaches(): void
    {
        if (isset(static::$CACHE_KEY_SELECT) && static::$CACHE_KEY_SELECT) {
            Cache::forget(static::$CACHE_KEY_SELECT);
        }

        if (isset(static::$CACHE_KEYS) && is_array(static::$CACHE_KEYS)) {
            foreach (static::$CACHE_KEYS as $key) {
                Cache::forget($key);
            }
        }
    }

    public static function clearDependentCaches(): void
    {
        $dependents = [
            SpesifikasiProduct::class,
            SPKMarketing::class,
            JadwalProduksi::class,
            // PermintaanAlatDanBahan::class,
            // PengecekanMaterialSS::class,
            // PengecekanMaterialElectrical::class,
            // PenyerahanProdukJadi::class,
            // QCPassed::class,
            // Ketidaksesuaian::class,
        ];

        foreach ($dependents as $model) {
            if (method_exists($model, 'clearModelCaches')) {
                $model::clearModelCaches();
            }
        }
    }
    protected static function bootHasCacheManager(): void
    {
        static::saved(fn() => static::clearDependentCaches());
        static::deleted(fn() => static::clearDependentCaches());
    }
}
