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
        'status_pekerjaan',
        'catatan_tambahan',
    ];

    public function spkService()
    {
        return $this->belongsTo(SPKService::class, 'spk_service_id');
    }
}
