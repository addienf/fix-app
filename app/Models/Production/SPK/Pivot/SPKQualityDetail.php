<?php

namespace App\Models\Production\SPK\Pivot;

use App\Models\Production\SPK\SPKQuality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKQualityDetail extends Model
{
    use HasFactory;

    protected $table = 'spk_quality_details';

    protected $fillable = [
        'spk_qualities_id',
        'nama_produk',
        'jumlah',
        'no_urs',
        'rencana_pengiriman',
    ];

    public function spkQC()
    {
        return $this->belongsTo(SPKQuality::class);
    }
}
