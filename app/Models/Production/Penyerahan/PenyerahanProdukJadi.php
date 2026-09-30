<?php

namespace App\Models\Production\Penyerahan;

use App\Models\Production\Penyerahan\Pivot\PenyerahanProdukJadiDetail;
use App\Models\Production\Penyerahan\Pivot\PenyerahanProdukJadiPIC;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyerahanProdukJadi extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_qualities_id',
        'tanggal',
        'penanggug_jawab',
        'penerima',
        'kondisi_produk',
        'catatan_tambahan',
        'status_penerimaan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function spkQC()
    {
        return $this->belongsTo(SPKQuality::class, 'spk_qualities_id');
    }

    public function pengecekanPerforma()
    {
        return $this->hasOne(PengecekanPerforma::class, 'produk_jadi_id');
    }

    public function details()
    {
        return $this->hasMany(PenyerahanProdukJadiDetail::class, 'produk_jadi_id');
    }

    public function pic()
    {
        return $this->hasOne(PenyerahanProdukJadiPIC::class, 'produk_jadi_id');
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
