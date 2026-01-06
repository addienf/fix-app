<?php

namespace App\Models\General;

use App\Models\Engineering\Complain\Complain;
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

    public function customer()
    {
        return $this->hasOne(Company::class, 'company_id');
    }

    public function complains()
    {
        return $this->hasMany(Complain::class, 'company_id');
    }
}
