<?php

namespace App\Models\Quality\PengecekanMaterial\Electrical\Pivot;

use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanMaterialElectricalDetail extends Model
{
    use HasFactory;

    protected $table = 'electrical_details';

    protected $fillable = [
        'pengecekan_electrical_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function pengecekanElectrical()
    {
        return $this->belongsTo(PengecekanMaterialElectrical::class, 'pengecekan_electrical_id');
    }
}
