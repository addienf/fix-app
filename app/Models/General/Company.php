<?php

namespace App\Models\General;

use App\Models\Engineering\Complain\Complain;
use App\Models\Sales\URS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    // public function customer()
    // {
    //     return $this->hasOne(Company::class, 'company_id');
    // }

    public function urs()
    {
        return $this->hasOne(URS::class, 'company_id');
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'company_id');
    }
}
