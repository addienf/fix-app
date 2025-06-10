<?php

namespace App\Models\Quality\PengecekanMaterial\SS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanMaterialSSDetail extends Model
{
    use HasFactory;

    protected $table = 'pengecekan_material_ss_details';

    protected $fillable = [
        'pengecekan_material_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function pengecekanSS()
    {
        return $this->belongsTo(PengecekanMaterialSS::class, 'pengecekan_material_id');
    }
}
