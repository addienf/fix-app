<?php

namespace App\Models\Quality\IncommingMaterial\MaterialNonSS\Pivot;

use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialNonSSDetail extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_non_ss_details';

    protected $fillable = [
        'material_non_ss_id',
        'details',
        'details_tambahan',
    ];

    protected $casts = [
        'details' => 'array',
        'details_tambahan' => 'array',
    ];

    public function productNonSS()
    {
        return $this->belongsTo(IncommingMaterialNonSS::class, 'material_non_ss_id');
    }
}
