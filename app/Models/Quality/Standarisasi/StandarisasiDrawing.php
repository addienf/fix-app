<?php

namespace App\Models\Quality\Standarisasi;

use App\Models\Quality\Standarisasi\Pivot\IdentitasGambarKerja;
use App\Models\Quality\Standarisasi\Pivot\PemeriksaanGambar;
use App\Models\Quality\Standarisasi\Pivot\StandarisasiDrawingDetail;
use App\Models\Quality\Standarisasi\Pivot\StandarisasiDrawingPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarisasiDrawing extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_marketing_id',
        'tanggal',
        'jenis_gambar',
        'format_gambar',
        'status_pemeriksaan',
    ];

    protected $casts = [
        'jenis_gambar' => 'array',
        'format_gambar' => 'array',
        'tanggal' => 'date',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function identitas()
    {
        return $this->hasOne(IdentitasGambarKerja::class, 'standarisasi_drawing_id');
    }

    public function pic()
    {
        return $this->hasOne(StandarisasiDrawingPIC::class, 'standarisasi_drawing_id');
    }

    public function detail()
    {
        return $this->hasOne(StandarisasiDrawingDetail::class, 'standarisasi_drawing_id');
    }

    public function pemeriksaan()
    {
        return $this->hasOne(PemeriksaanGambar::class, 'standarisasi_drawing_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->check_signature &&
                $model->status_pemeriksaan !== 'Diperiksa'
            ) {
                $model->status_pemeriksaan = 'Diperiksa';
            }
        });

        static::deleting(function ($model) {
            if ($model->detail) {
                $model->detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->identitas) {
                $model->identitas->delete();
            }
        });
    }
}
