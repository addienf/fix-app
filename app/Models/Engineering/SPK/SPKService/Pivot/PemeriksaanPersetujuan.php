<?php

namespace App\Models\Engineering\SPK\SPKService\Pivot;

use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanPersetujuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_service_id',
        'nama_alat',
        'tipe',
        'nomor_seri',
        'resolusi',
        'titik_ukur',
        'quantity',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }
}
