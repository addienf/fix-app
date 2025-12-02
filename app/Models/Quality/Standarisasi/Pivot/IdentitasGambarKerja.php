<?php

namespace App\Models\Quality\Standarisasi\Pivot;

use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentitasGambarKerja extends Model
{
    use HasFactory;

    protected $fillable = [
        'standarisasi_drawing_id',
        'judul_gambar',
        'no_gambar',
        'tanggal_pembuatan',
        'revisi',
        'revisi_ke',
        'nama_pembuat',
        'nama_pemeriksa',
    ];

    protected $casts = [
        'tanggal_pembuatan' => 'date',
    ];

    public function standarisasi()
    {
        return $this->belongsTo(StandarisasiDrawing::class, 'standarisasi_drawing_id');
    }
}
