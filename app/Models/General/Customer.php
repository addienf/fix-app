<?php

namespace App\Models\General;

use App\Models\Sales\URS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'company_name',
        'company_address'
    ];

    public function urs()
    {
        return $this->hasOne(URS::class);
    }
}
