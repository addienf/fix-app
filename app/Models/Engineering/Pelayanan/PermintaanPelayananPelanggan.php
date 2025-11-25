<?php

namespace App\Models\Engineering\Pelayanan;

use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Pelayanan\Pivot\PermintaanPelayananPelangganDetail;
use App\Models\Engineering\Pelayanan\Pivot\PermintaanPelayananPelangganPIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPelayananPelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'complain_id',
        'no_form',
        'tanggal',
        'alamat',
        'perusahaan',
        'jenis_permintaan',
        'jenis_permintaan_lainnya',
        'tanggal_pelaksanaan',
        'tempat_pelaksanaan',
        'no_kontak',
        'status',
    ];

    protected $casts = [
        'jenis_permintaan' => 'array'
    ];

    public function spkService()
    {
        return $this->hasOne(SPKService::class, 'pelayanan_id');
    }

    public function complain()
    {
        return $this->belongsTo(Complain::class, 'complain_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanPelayananPelangganDetail::class, 'pelayanan_id');
    }

    public function pic()
    {
        return $this->hasOne(PermintaanPelayananPelangganPIC::class, 'pelayanan_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->diterima_signature &&
                $model->status !== 'Diterima'
            ) {
                $model->status = 'Diterima';
            }

            if (
                $model->pic?->dibuat_signature &&
                $model->status !== 'Dibuat'
            ) {
                $model->status = 'Dibuat';
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            $model->pic?->delete();
        });
    }
}
