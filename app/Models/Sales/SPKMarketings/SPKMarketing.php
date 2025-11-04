<?php

namespace App\Models\Sales\SPKMarketings;

use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Production\SPK\SPKVendor;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\Pivot\SPKMarketingPIC;
use App\Models\Warehouse\Pelabelan\QCPassed;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property string|null $id
 * @property string|null $spesifikasi_product_id
 * @property string|null $no_spk
 * @property string|null $no_order
 * @property string|null $tanggal
 * @property string|null $dari
 * @property string|null $kepada
 * @property string|null $status_penerimaan
 */
class SPKMarketing extends Model
{
    use HasFactory, HasCacheManager;

    protected $table = 'spk_marketings';

    protected $fillable = [
        'spesifikasi_product_id',
        'no_spk',
        'tanggal',
        'no_order',
        'dari',
        'kepada',
        'status_penerimaan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function spesifikasiProduct()
    {
        return $this->belongsTo(SpesifikasiProduct::class);
    }

    public function pic()
    {
        return $this->hasOne(SPKMarketingPIC::class, 'spk_marketing_id');
    }

    public function jadwalProduksi()
    {
        return $this->hasOne(JadwalJadwalProduksi::class, 'spk_marketing_id');
    }

    public function permintaan()
    {
        return $this->hasOne(PermintaanAlatDanBahan::class, 'spk_marketing_id');
    }

    public function standarisasi()
    {
        return $this->hasOne(StandarisasiDrawing::class, 'spk_marketing_id');
    }

    public function kelengkapanSS()
    {
        return $this->hasOne(KelengkapanMaterialSS::class, 'spk_marketing_id');
    }

    public function pengecekanSS()
    {
        return $this->hasOne(PengecekanMaterialSS::class, 'spk_marketing_id');
    }

    public function defect()
    {
        return $this->hasOne(DefectStatus::class, 'spk_marketing_id');
    }

    public function pengecekanElectrical()
    {
        return $this->hasOne(PengecekanMaterialElectrical::class, 'spk_marketing_id');
    }

    public function spkQC()
    {
        return $this->hasOne(SPKQuality::class, 'spk_marketing_id');
    }

    public function produkJadi()
    {
        return $this->hasOne(PenyerahanProdukJadi::class, 'spk_marketing_id');
    }

    public function pengecekanPerforma()
    {
        return $this->hasOne(PengecekanPerforma::class, 'spk_marketing_id');
    }

    public function qc()
    {
        return $this->hasOne(QCPassed::class, 'spk_marketing_id');
    }

    public function spkVendor()
    {
        return $this->hasOne(SPKVendor::class, 'spk_marketing_id');
    }

    public function ketidaksesuaian()
    {
        return $this->hasOne(Ketidaksesuaian::class, 'spk_marketing_id');
    }

    public function getSelectCacheConfig(): array
    {
        return [
            [
                'relation' => 'urs',
                'title' => 'no_urs',
                'limit' => 10,
            ],
        ];
    }

    // protected static function booted()
    // {
    //     static::saving(function ($model) {
    //         if (
    //             $model->pic?->receive_signature &&
    //             $model->status_penerimaan !== 'Diterima'
    //         ) {
    //             $model->status_penerimaan = 'Diterima';
    //         }
    //     });

    //     static::deleting(function ($spesifikasi) {
    //         if ($spesifikasi->pic) {
    //             $spesifikasi->pic->delete();
    //         }
    //     });

    //     static::saved(fn() => Cache::forget(SpesifikasiProduct::CACHE_KEY_SELECT));
    //     static::deleted(fn() => Cache::forget(SpesifikasiProduct::CACHE_KEY_SELECT));
    // }

    // public static string $CACHE_KEY_SELECT_PERMINTAAN = 'select_spk_marketing_disetujui_no_permintaan';

    // public static array $CACHE_KEYS = [
    //     'jadwal_produksi'      => 'spk_marketing_ke_jadwal',
    //     'permintaan_bahan'     => 'spk_marketing_ke_permintaan',
    //     'pengecekan_ss'        => 'spk_marketing_ke_ss',
    //     'pengecekan_electrical' => 'spk_marketing_ke_electrical',
    //     'produk_jadi'          => 'spk_marketing_ke_produk_jadi',
    //     'pengecekan_performa'  => 'spk_marketing_ke_performa',
    //     'qc_passed'            => 'spk_marketing_ke_qc_passed',
    //     'vendor'               => 'spk_marketing_ke_vendor',
    //     'ketidaksesuaian'      => 'spk_marketing_ke_ketidaksesuaian',
    // ];

    // protected static function booted()
    // {
    //     static::saving(function ($model) {
    //         if (
    //             $model->pic?->receive_signature &&
    //             $model->status_penerimaan !== 'Diterima'
    //         ) {
    //             $model->status_penerimaan = 'Diterima';
    //         }
    //     });

    //     static::deleting(function ($spk) {
    //         if ($spk->pic) {
    //             $spk->pic->delete();
    //         }
    //     });

    //     // 🔁 Reset cache dropdown Spesifikasi Product
    //     static::saved(function () {
    //         SpesifikasiProduct::clearModelCaches();
    //         static::clearDependentCaches();
    //     });

    //     static::deleted(function () {
    //         SpesifikasiProduct::clearModelCaches();
    //         static::clearDependentCaches();
    //     });
    // }

    public static array $CACHE_KEYS = [
        'jadwal_produksi'       => 'spk_marketing_ke_jadwal',
        'permintaan_bahan'      => 'spk_marketing_ke_permintaan',
        'pengecekan_ss'         => 'spk_marketing_ke_ss',
        'pengecekan_electrical' => 'spk_marketing_ke_electrical',
        'produk_jadi'           => 'spk_marketing_ke_produk_jadi',
        'pengecekan_performa'   => 'spk_marketing_ke_performa',
        'qc_passed'             => 'spk_marketing_ke_qc_passed',
        'vendor'                => 'spk_marketing_ke_vendor',
        'ketidaksesuaian'       => 'spk_marketing_ke_ketidaksesuaian',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->receive_signature &&
                $model->status_penerimaan !== 'Diterima'
            ) {
                $model->status_penerimaan = 'Diterima';
            }
        });

        static::deleting(function ($spk) {
            $spk->pic?->delete();
        });

        static::saved(function () {
            SpesifikasiProduct::clearModelCaches();
        });

        static::deleted(function () {
            SpesifikasiProduct::clearModelCaches();
        });
    }
}
