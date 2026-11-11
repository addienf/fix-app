<?php

namespace App\Models\Engineering\Berita;

use App\Models\Engineering\Berita\Pivot\BeritaAcaraDetail;
use App\Models\Engineering\Berita\Pivot\BeritaAcaraPIC;
use App\Models\Engineering\Berita\Pivot\Pelanggan;
use App\Models\Engineering\Berita\Pivot\PenyediaJasa;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'no_surat',
        'tanggal',
        'status_po',
        'nomor_po',
    ];


    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }

    public function detail()
    {
        return $this->hasOne(BeritaAcaraDetail::class, 'berita_id');
    }

    public function pic()
    {
        return $this->hasOne(BeritaAcaraPIC::class, 'berita_id');
    }

    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'berita_id');
    }

    public function penyediaJasa()
    {
        return $this->hasOne(PenyediaJasa::class, 'berita_id');
    }

    protected static function booted(): void
    {
        static::created(function (self $berita) {
            $spk = $berita->spkService;
            if ($spk && $spk->lama_pelaksanaan > 0) {
                $spk->decrement('lama_pelaksanaan');
            }
        });
        static::deleted(function (self $berita) {
            $berita->spkService?->increment('lama_pelaksanaan');
        });
    }
}
