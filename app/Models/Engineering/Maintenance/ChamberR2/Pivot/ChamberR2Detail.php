<?php

namespace App\Models\Engineering\Maintenance\ChamberR2\Pivot;

use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberR2Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'r2_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function chamberR2()
    {
        return $this->belongsTo(ChamberR2::class, 'r2_id');
    }
}
