<?php

namespace App\Models\Engineering\Maintenance\RissingPipette\Pivot;

use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RissingPipetteDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rissing_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function rissing()
    {
        return $this->belongsTo(RissingPipette::class, 'rissing_id');
    }
}
