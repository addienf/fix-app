<?php

namespace App\Models\Engineering\Permintaan\Pivot;

use App\Models\Engineering\Permintaan\PermintaanSparepart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanSparepartPIC extends Model
{
    use HasFactory;

    protected $table = 'permintaan_sparepart_pics';

    protected $fillable = [
        'sparepart_id',
        'dibuat_ttd',
        'dibuat_name',
        'diketahui_ttd',
        'diketahui_name',
        'diserahkan_ttd',
        'diserahkan_name',
    ];

    public function permintaanSparepart()
    {
        return $this->belongsTo(PermintaanSparepart::class, 'sparepart_id');
    }
}
