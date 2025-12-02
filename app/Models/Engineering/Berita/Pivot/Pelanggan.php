<?php

namespace App\Models\Engineering\Berita\Pivot;

use App\Models\Engineering\Berita\BeritaAcara;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'berita_id',
        'nama',
        'perusahaan',
        'alamat',
        'jabatan',
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_id');
    }
}
