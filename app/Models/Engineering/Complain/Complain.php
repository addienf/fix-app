<?php

namespace App\Models\Engineering\Complain;

use App\Models\Engineering\Complain\Pivot\ComplainDetail;
use App\Models\Engineering\Complain\Pivot\ComplainPIC;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Models\General\Company;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory, HasCacheManager;

    protected $fillable = [
        'form_no',
        'tanggal',
        'dari',
        'kepada',
        'name_complain',
        // 'company_name',
        'company_id',
        'department',
        'phone_number',
        'receive_by',
    ];

    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function pelayananPelanggan()
    {
        return $this->hasOne(PermintaanPelayananPelanggan::class, 'complain_id');
    }


    public function details()
    {
        return $this->hasMany(ComplainDetail::class, 'complain_id');
    }

    public function pic()
    {
        return $this->hasOne(ComplainPIC::class, 'complain_id');
    }
}
