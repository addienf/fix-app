<?php

namespace App\Models\Engineering\SPK;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService\Pivot\PemeriksaanPersetujuan;
use App\Models\Engineering\SPK\SPKService\Pivot\Petugas;
use App\Models\Engineering\SPK\SPKService\Pivot\SPKServicePIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKService extends Model
{
    use HasFactory;

    protected $table = 'spk_services';

    protected $fillable = [
        'no_complaint_form',
        'no_spk_service',
        'tanggal',
        'alamat',
        'perusahaan',
        'deskripsi_pekerjaan',
        'jadwal_pelaksana',
        'waktu_selesai',
        'status_penyelesaian',
    ];

    protected $casts = [
        'jadwal_pelaksana' => 'datetime',
        'waktu_selesai' => 'datetime',
        'deskripsi_pekerjaan' => 'array'
    ];

    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'spk_service_id');
    }

    public function pemeriksaanPersetujuan()
    {
        return $this->hasOne(PemeriksaanPersetujuan::class, 'spk_service_id');
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

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->diketahui_ttd &&
                $model->status_penyelesaian !== 'Selesai'
            ) {
                $model->status_penyelesaian = 'Selesai';
            }
        });

        // static::deleting(function ($spesifikasi) {
        //     if ($spesifikasi->pic) {
        //         $spesifikasi->pic->delete();
        //     }
        // });
    }
}
