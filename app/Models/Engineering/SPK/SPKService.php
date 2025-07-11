<?php

namespace App\Models\Engineering\SPK;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Permintaan\PermintaanSparepart;
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
