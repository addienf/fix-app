<?php

namespace App\Models\Quality\IncommingMaterial\MaterialSS\Pivot;

use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialSSSummary extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_ss_summaries';

    protected $fillable = [
        'material_ss_id',
        'summary',
    ];

    protected $casts = [
        'summary' => 'array',
    ];

    public function productSS()
    {
        return $this->belongsTo(IncommingMaterialSS::class, 'material_ss_id');
    }
}
