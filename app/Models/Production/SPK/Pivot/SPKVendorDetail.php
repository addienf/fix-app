<?php

namespace App\Models\Production\SPK\Pivot;

use App\Models\Production\SPK\SPKVendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKVendorDetail extends Model
{
    use HasFactory;

    protected $table = 'spk_vendor_details';

    protected $fillable = [
        'spk_vendor_id',
        'nama_bahan',
        'spesifikasi',
        'jumlah',
        'keperluan',
    ];

    public function spkVen()
    {
        return $this->belongsTo(SPKVendor::class);
    }
}
