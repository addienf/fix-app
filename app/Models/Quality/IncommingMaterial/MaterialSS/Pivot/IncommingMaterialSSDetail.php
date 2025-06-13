<?php

namespace App\Models\Quality\IncommingMaterial\MaterialSS\Pivot;

use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncommingMaterialSSDetail extends Model
{
    use HasFactory;

    protected $table = 'incomming_material_ss_details';

    protected $fillable = [
        'material_ss_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function productSS()
    {
        return $this->belongsTo(IncommingMaterialSS::class, 'material_ss_id');
    }
}
