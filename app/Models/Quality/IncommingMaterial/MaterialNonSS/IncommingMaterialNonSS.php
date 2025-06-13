<?php

namespace App\Models\Quality\IncommingMaterial\MaterialNonSS;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\Pivot\IncommingMaterialNonSSDetail;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\Pivot\IncommingMaterialNonSSPIC;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\Pivot\IncommingMaterialNonSSSummary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialNonSS extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_non_ss';

    protected $fillable = [
        'permintaan_pembelian_id',
        'no_po',
        'supplier',
        'batch_no',
    ];

    public function permintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }

    public function detail()
    {
        return $this->hasOne(IncommingMaterialNonSSDetail::class, 'material_non_ss_id');
    }

    public function pic()
    {
        return $this->hasOne(IncommingMaterialNonSSPIC::class, 'material_non_ss_id');
    }

    public function summary()
    {
        return $this->hasOne(IncommingMaterialNonSSSummary::class, 'material_non_ss_id');
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
