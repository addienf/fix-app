<?php

namespace App\Models\Sales\SPKMarketings;

use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\Pivot\SPKMarketingPIC;
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

    protected static function booted()
    {
        static::deleting(function ($spesifikasi) {
            if ($spesifikasi->pic) {
                $spesifikasi->pic->delete();
            }
        });
    }
}
