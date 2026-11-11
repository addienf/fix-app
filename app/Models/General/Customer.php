<?php

namespace App\Models\General;

use App\Models\Sales\URS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    use HasFactory;

    protected static $factory = \Database\Factories\General\CustomerFactory::class;

    protected $fillable = [
        'company_id',
        'name',
        'department',
    ];

    public function urs()
    {
        return $this->hasMany(URS::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
