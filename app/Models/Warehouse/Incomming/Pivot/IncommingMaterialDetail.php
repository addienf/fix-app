<?php

namespace App\Models\Warehouse\Incomming\Pivot;

use App\Models\Warehouse\Incomming\IncommingMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'incomming_material_id',
        'nama_material',
        'batch_no',
        'jumlah',
        'satuan',
        'kondisi_material',
        'status_qc',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function incommingMaterial()
    {
        return $this->belongsTo(IncommingMaterial::class, 'incomming_material_id');
    }
}
