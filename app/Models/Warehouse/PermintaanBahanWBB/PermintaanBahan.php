<?php

namespace App\Models\Warehouse\PermintaanBahanWBB;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Warehouse\PermintaanBahanWBB\Pivot\PermintaanBahanDetail;
use App\Models\Warehouse\PermintaanBahanWBB\Pivot\PermintaanBahanPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanBahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_bahan_pro_id',
        'tanggal',
        'no_surat',
        'dari',
        'kepada',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function permintaanBahanPro()
    {
        return $this->belongsTo(PermintaanAlatDanBahan::class, 'permintaan_bahan_pro_id');
    }

    public function details()
    {
        return $this->hasMany(PermintaanBahanDetail::class, 'permintaan_bahan_wbb_id');
    }

    public function pic()
    {
        return $this->hasOne(PermintaanBahanPIC::class, 'permintaan_bahan_wbb_id');
    }

    protected static function booted()
    {
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
