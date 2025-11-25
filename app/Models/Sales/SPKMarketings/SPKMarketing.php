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
use Illuminate\Support\Facades\Log;

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
    protected static $factory = SPKMarketing::class;

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
    }
}
