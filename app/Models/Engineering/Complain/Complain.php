<?php

namespace App\Models\Engineering\Complain;

use App\Models\Engineering\Complain\Pivot\ComplainDetail;
use App\Models\Engineering\Complain\Pivot\ComplainPIC;
use App\Models\Engineering\SPK\SPKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_no',
        'tanggal',
        'dari',
        'kepada',
        'name_complain',
        'company_name',
        'department',
        'phone_number',
        'receive_by',
    ];

    public function spkService()
    {
        return $this->hasOne(SPKService::class, 'complain_id');
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
