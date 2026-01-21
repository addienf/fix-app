<?php

namespace App\Models\Production\Jadwal;

use App\Models\Production\Jadwal\Pivot\IdentifikasiProduk;
use App\Models\Production\Jadwal\Pivot\JadwalProduksiDetail;
use App\Models\Production\Jadwal\Pivot\JadwalProduksiPIC;
use App\Models\Production\Jadwal\Pivot\SumberDaya;
use App\Models\Production\Jadwal\Pivot\TimelineProduksi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKVendor;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string|null $id
 * @property string|null $spk_marketing_id
 * @property string|null $tanggal
 * @property string|null $pic_name
 * @property string|null $status_persetujuan
 */
class JadwalProduksi extends Model
{
    use HasFactory, HasCacheManager;

    protected $fillable = [
        'spk_marketing_id',
        'tanggal',
        'pic_name',
        'no_surat',
        'file_upload',
        'status_persetujuan',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function permintaanBahanProduksi()
    {
        return $this->hasOne(PermintaanAlatDanBahan::class, 'jadwal_id');
    }

    public function details()
    {
        return $this->hasMany(JadwalProduksiDetail::class);
    }

    public function timelines()
    {
        return $this->hasMany(TimelineProduksi::class);
    }

    public function identifikasiProduks()
    {
        return $this->hasMany(IdentifikasiProduk::class);
    }

    public function sumbers()
    {
        return $this->hasMany(SumberDaya::class);
    }

    public function permintaanBahanWBB()
    {
        return $this->hasOne(PermintaanBahan::class, 'perencanaan_id');
    }

    public function serahTerimaBahan()
    {
        return $this->hasOne(SerahTerimaBahan::class, 'perencanaan_id');
    }

    public function spkVendor()
    {
        return $this->hasOne(SPKVendor::class, 'perencanaan_id');
    }

    // public function sumbersElectrical()
    // {
    //     return $this->hasMany(SumberDaya::class)
    //         ->where('kategori', 'electrical');
    // }

    // public function sumbersUps()
    // {
    //     return $this->hasMany(SumberDaya::class)
    //         ->where('kategori', 'ups');
    // }

    // public function sumbersMechanical()
    // {
    //     return $this->hasMany(SumberDaya::class)
    //         ->where('kategori', 'mechanical');
    // }

    public function pic()
    {
        return $this->hasOne(JadwalProduksiPIC::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->approve_signature &&
                $model->status_persetujuan !== 'Disetujui'
            ) {
                $model->status_persetujuan = 'Disetujui';
            }
        });

        static::updating(function ($model) {
            if (
                $model->isDirty('file_upload') &&
                $model->getOriginal('file_upload') &&
                Storage::disk('public')->exists($model->getOriginal('file_upload'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('file_upload'));
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            foreach ($model->sumbers as $sumber) {
                $sumber->delete();
            }

            if ($model->file_upload && Storage::disk('public')->exists($model->file_upload)) {
                Storage::disk('public')->delete($model->file_upload);
            }
        });
    }
}
