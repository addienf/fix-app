<?php

namespace App\Models\Warehouse\SerahTerima;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use App\Models\Warehouse\SerahTerima\Pivot\SerahTerimaBahanDetail;
use App\Models\Warehouse\SerahTerima\Pivot\SerahTerimaBahanPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerahTerimaBahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_alat_id',
        'tanggal',
        'no_surat',
        'dari',
        'kepada',
        'status_penerimaan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function peminjamanAlat()
    {
        return $this->belongsTo(PeminjamanAlat::class, 'peminjaman_alat_id');
    }

    public function standarisasiDrawing()
    {
        return $this->hasOne(StandarisasiDrawing::class, 'serah_terima_bahan_id');
    }

    public function details()
    {
        return $this->hasMany(SerahTerimaBahanDetail::class);
    }

    public function pic()
    {
        return $this->hasOne(SerahTerimaBahanPIC::class);
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

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
