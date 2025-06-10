<?php

namespace App\Models\Quality\Defect\Pivot;

use App\Models\Quality\Defect\DefectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectStatusDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'defect_status_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function defect()
    {
        return $this->belongsTo(DefectStatus::class);
    }
}
