<?php

namespace App\Models\Engineering\Maintenance\ChamberG2\Pivot;

use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberG2Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'g2_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function chamberG2()
    {
        return $this->belongsTo(ChamberG2::class, 'g2_id');
    }
}
