<?php

namespace App\Models\Engineering\Maintenance\ChamberWalkinG2\Pivot;

use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamberWalkinG2Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'walk_in_g2_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function walkinG2()
    {
        return $this->belongsTo(ChamberWalkinG2::class, 'walk_in_g2_id');
    }
}
