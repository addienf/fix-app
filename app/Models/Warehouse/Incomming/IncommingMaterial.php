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
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }

    public function detail()
    {
        return $this->hasOne(IncommingMaterialDetail::class, 'incomming_material_id');
    }

    public function pic()
    {
        return $this->hasOne(IncommingMaterialPIC::class, 'incomming_material_id');
    }

    protected static function booted()
    {
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
