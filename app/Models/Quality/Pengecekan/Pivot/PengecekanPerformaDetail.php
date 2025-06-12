<?php

namespace App\Models\Quality\Pengecekan\Pivot;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengecekanPerformaDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengecekan_performa_id',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function pengecekanPerforma()
    {
        return $this->belongsTo(PengecekanPerforma::class, 'pengecekan_performa_id');
    }
}
