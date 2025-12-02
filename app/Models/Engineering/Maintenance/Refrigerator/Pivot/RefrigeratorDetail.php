<?php

namespace App\Models\Engineering\Maintenance\Refrigerator\Pivot;

use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefrigeratorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'refrigerator_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class, 'refrigerator_id');
    }
}
