<?php

namespace App\Models\Engineering\Maintenance\ColdRoom\Pivot;

use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColdRoomDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cold_id',
        'checklist',
    ];

    protected $casts = [
        'checklist' => 'array'
    ];

    public function coldRoom()
    {
        return $this->belongsTo(ColdRoom::class, 'cold_id');
    }
}
