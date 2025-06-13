<?php

namespace App\Models\Quality\IncommingMaterial\MaterialSS;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialSS\Pivot\IncommingMaterialSSDetail;
use App\Models\Quality\IncommingMaterial\MaterialSS\Pivot\IncommingMaterialSSPIC;
use App\Models\Quality\IncommingMaterial\MaterialSS\Pivot\IncommingMaterialSSSummary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialSS extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_ss';

    protected $fillable = [
        'permintaan_pembelian_id',
        'no_po',
        'supplier',
        'remark',
    ];

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }

    public function detail()
    {
        return $this->hasOne(IncommingMaterialSSDetail::class, 'material_ss_id');
    }

    public function pic()
    {
        return $this->hasOne(IncommingMaterialSSPIC::class, 'material_ss_id');
    }

    public function summary()
    {
        return $this->hasOne(IncommingMaterialSSSummary::class, 'material_ss_id');
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

            if ($model->summary) {
                $model->summary->delete();
            }
        });
    }
}
