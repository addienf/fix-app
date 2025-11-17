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
use Illuminate\Support\Facades\Log;

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

    public static function newClearModelCaches(): void
    {
        // Hapus key static biasa
        if (isset(static::$CACHE_KEYS) && is_array(static::$CACHE_KEYS)) {
            foreach (static::$CACHE_KEYS as $key) {
                Cache::forget($key);
            }
        }

        // Hapus semua cache search berdasarkan prefix
        if (isset(static::$CACHE_PREFIXES) && is_array(static::$CACHE_PREFIXES)) {

            foreach (static::$CACHE_PREFIXES as $prefix) {
                $files = glob(storage_path("framework/cache/data/*{$prefix}*"));

                if ($files) {
                    foreach ($files as $file) {
                        @unlink($file);
                    }
                }
            }
        }
    }
}
