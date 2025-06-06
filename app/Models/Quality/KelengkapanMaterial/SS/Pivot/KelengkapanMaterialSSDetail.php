<?php

namespace App\Models\Quality\KelengkapanMaterial\SS\Pivot;

use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelengkapanMaterialSSDetail extends Model
{
    use HasFactory;

    protected $table = 'kelengkapan_material_ss_details';

    protected $fillable = [
        'kelengkapan_material_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function kelengpakanSS()
    {
        return $this->belongsTo(KelengkapanMaterialSS::class, 'kelengkapan_material_id');
    }
}
