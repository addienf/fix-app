<?php

namespace App\Models\Quality\Penyerahan;

use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Models\Quality\Penyerahan\Pivot\PenerimaElectrical;
use App\Models\Quality\Penyerahan\Pivot\PenyerahanElectricalPIC;
use App\Models\Quality\Penyerahan\Pivot\SebelumSerahTerima;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyerahanElectrical extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengecekan_material_id',
        'nama_produk',
        'kode_produk',
        'no_seri',
        'tanggal_selesai',
        'jumlah',
        'kondisi',
        'deskripsi_kondisi',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date'
    ];

    public function pengecekanSS()
    {
        return $this->belongsTo(PengecekanMaterialSS::class, 'pengecekan_material_id');
    }

    public function pic()
    {
        return $this->hasOne(PenyerahanElectricalPIC::class, 'penyerahan_electrical_id');
    }

    public function sebelumSerahTerima()
    {
        return $this->hasOne(SebelumSerahTerima::class, 'penyerahan_electrical_id');
    }

    public function penerimaElectrical()
    {
        return $this->hasOne(PenerimaElectrical::class, 'penyerahan_electrical_id');
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
