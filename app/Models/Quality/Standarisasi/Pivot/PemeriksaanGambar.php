<?php

namespace App\Models\Quality\Standarisasi\Pivot;

use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanGambar extends Model
{
    use HasFactory;

    protected $fillable = [
        'standarisasi_drawing_id',
        'pemeriksaan_komponen',
    ];

    protected $casts = [
        'pemeriksaan_komponen' => 'array',
    ];

    public function standarisasi()
    {
        return $this->belongsTo(StandarisasiDrawing::class, 'standarisasi_drawing_id');
    }
}
