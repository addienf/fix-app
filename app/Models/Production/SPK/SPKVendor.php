<?php

namespace App\Models\Production\SPK;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKVendor extends Model
{
    use HasFactory;

    protected $table = 'spk_vendors';

    protected $fillable = [
        'spk_marketing_id',
        'nama_perusahaan',
        'file_path',
        'lampiran',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }
}
