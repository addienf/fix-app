<?php

namespace App\Models\Production\SPK;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPKVendor extends Model
{
    use HasFactory;

    protected $table = 'spk_vendors';

    protected $fillable = [
        'no_spk_vendor',
        'nama_perusahaan',
    ];
}
