<?php

namespace App\Models\Sales\SPKMarketings;

use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Quality\Defect\DefectStatus;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\Pivot\SPKMarketingPIC;
use App\Models\Warehouse\Pelabelan\QCPassed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKMarketing extends Model
{
    use HasFactory;

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

        static::deleting(function ($spesifikasi) {
            if ($spesifikasi->pic) {
                $spesifikasi->pic->delete();
            }
        });
    }
}
