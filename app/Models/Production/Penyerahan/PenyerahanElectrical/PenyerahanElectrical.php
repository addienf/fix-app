<?php

namespace App\Models\Production\Penyerahan\PenyerahanElectrical;

use App\Models\Production\Penyerahan\PenyerahanElectrical\Pivot\PenerimaElectrical;
use App\Models\Production\Penyerahan\PenyerahanElectrical\Pivot\PenyerahanElectricalPIC;
use App\Models\Production\Penyerahan\PenyerahanElectrical\Pivot\SebelumSerahTerima;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
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
