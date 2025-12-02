<?php

namespace App\Models\Quality\IncommingMaterial\MaterialNonSS\Pivot;

use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialNonSSSummary extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_non_ss_summaries';

    protected $fillable = [
        'material_non_ss_id',
        'summary',
    ];

    protected $casts = [
        'summary' => 'array',
    ];

    public function productNonSS()
    {
        return $this->belongsTo(IncommingMaterialNonSS::class, 'material_non_ss_id');
    }
}
