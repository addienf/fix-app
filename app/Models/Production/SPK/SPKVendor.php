<?php

namespace App\Models\Production\SPK;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SPKVendor extends Model
{
    use HasFactory;

    protected $table = 'spk_vendors';

    protected $fillable = [
        'permintaan_bahan_pro_id',
        'no_spk_vendor',
        'nama_perusahaan',
        'file_path',
        'lampiran',
    ];

    protected $casts = [
        'lampiran' => 'array'
    ];

    public function peminjamanAlat()
    {
        return $this->hasOne(PeminjamanAlat::class, 'spk_vendor_id');
    }

    public function permintaanBahanProduksi()
    {
        return $this->belongsTo(PermintaanAlatDanBahan::class, 'permintaan_bahan_pro_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('file_path') &&
                $model->getOriginal('file_path') &&
                Storage::disk('public')->exists($model->getOriginal('file_path'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('file_path'));
            }

            if (
                $model->isDirty('lampiran') &&
                $model->getOriginal('lampiran') &&
                Storage::disk('public')->exists($model->getOriginal('lampiran'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('lampiran'));
            }
        });

        static::deleting(function ($model) {
            if ($model->file_path && Storage::disk('public')->exists($model->file_path)) {
                Storage::disk('public')->delete($model->file_path);
            }

            if ($model->lampiran && Storage::disk('public')->exists($model->lampiran)) {
                Storage::disk('public')->delete($model->lampiran);
            }
        });
    }
}
