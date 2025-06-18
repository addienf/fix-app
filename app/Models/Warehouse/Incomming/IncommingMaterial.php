<?php

namespace App\Models\Warehouse\Incomming;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Warehouse\Incomming\Pivot\IncommingMaterialDetail;
use App\Models\Warehouse\Incomming\Pivot\IncommingMaterialPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'permintaan_pembelian_id',
        'tanggal',
        'kondisi_material',
        'status_penerimaan',
        'dokumen_pendukung',
        'file_upload',
        'status_penerimaan_pic',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }

    public function details()
    {
        return $this->hasMany(IncommingMaterialDetail::class, 'incomming_material_id');
    }

    public function pic()
    {
        return $this->hasOne(IncommingMaterialPIC::class, 'incomming_material_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->received_signature &&
                $model->status_penerimaan_pic !== 'Diterima'
            ) {
                $model->status_penerimaan_pic = 'Diterima';

                $permintaanBahanPro = $model->permintaanPembelian
                    ?->permintaanBahanWBB
                    ?->permintaanBahanPro;

                if ($permintaanBahanPro) {
                    $permintaanBahanPro->status = 'Tersedia'; // contoh status
                    $permintaanBahanPro->save();
                }
            }
        });

        static::deleting(function ($model) {
            if ($model->detail) {
                $model->detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
