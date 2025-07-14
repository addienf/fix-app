<?php

namespace App\Models\Engineering\Maintenance\WalkinChamber\Pivot;

use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkinChamberDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'walk_in_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function walkinChamber()
    {
        return $this->belongsTo(WalkinChamber::class, 'walk_in_id');
    }
}
