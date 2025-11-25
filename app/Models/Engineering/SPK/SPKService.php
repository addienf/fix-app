<?php

namespace App\Models\Engineering\SPK;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService\Pivot\PemeriksaanPersetujuan;
use App\Models\Engineering\SPK\SPKService\Pivot\Petugas;
use App\Models\Engineering\SPK\SPKService\Pivot\SPKServicePIC;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKService extends Model
{
    use HasFactory, HasCacheManager;

    protected $table = 'spk_services';

    protected $fillable = [
        'pelayanan_id',
        'no_spk_service',
        'perusahaan',
        'alamat',
        'deskripsi_pekerjaan',
        'deskripsi_pekerjaan_lainnya',
        'tanggal_pelaksanaan',
        'tempat_pelaksanaan',
        'status',
    ];

    protected $casts = [
        'deskripsi_pekerjaan' => 'array'
    ];

    public function pelayananPelanggan()
    {
        return $this->belongsTo(PermintaanPelayananPelanggan::class, 'pelayanan_id');
    }

    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'spk_service_id');
    }

    public function details()
    {
        return $this->hasMany(PemeriksaanPersetujuan::class, 'spk_service_id');
    }

    public function beritaAcara()
    {
        return $this->hasOne(BeritaAcara::class, 'spk_service_id');
    }

    public function pic()
    {
        return $this->hasOne(SPKServicePIC::class, 'spk_service_id');
    }

    public function permintaanSparepart()
    {
        return $this->hasOne(PermintaanSparepart::class, 'spk_service_id');
    }

    public function walkinChamber()
    {
        return $this->hasOne(WalkinChamber::class, 'spk_service_id');
    }

    public function chamberR2()
    {
        return $this->hasOne(ChamberR2::class, 'spk_service_id');
    }

    public function refrigerator()
    {
        return $this->hasOne(Refrigerator::class, 'spk_service_id');
    }

    public function coldRoom()
    {
        return $this->hasOne(ColdRoom::class, 'spk_service_id');
    }

    public function rissing()
    {
        return $this->hasOne(RissingPipette::class, 'spk_service_id');
    }

    public function walkinG2()
    {
        return $this->hasOne(ChamberWalkinG2::class, 'spk_service_id');
    }

    public function chamberG2()
    {
        return $this->hasOne(ChamberG2::class, 'spk_service_id');
    }

    public function service()
    {
        return $this->hasOne(ServiceReport::class, 'spk_service_id');
    }

    public static array $CACHE_KEYS = [
        'permintaanSparepart'       => 'spk_service_permintaan_sparepart',
        'walkinChamber'             => 'spk_service_walking_chamber',
        'chamberR2'                 => 'spk_service_chamber_r2',
        'refrigerator'              => 'spk_service_refrigerator',
        'coldRoom'                  => 'spk_service_cold_room',
        'rissing'                   => 'spk_service_rissing',
        'walkinG2'                  => 'spk_service_walking_g2',
        'chamberG2'                 => 'spk_service_chamber_g2',
        'service'                   => 'spk_service_service',
        'beritaAcara'               => 'spk_service_berita_acara',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->dibuat_signature &&
                $model->status !== 'Selesai'
            ) {
                $model->status = 'Selesai';
            }
        });
    }
}
