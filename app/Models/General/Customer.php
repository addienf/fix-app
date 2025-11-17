<?php

namespace App\Models\General;

use App\Models\Sales\URS;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Customer extends Model
{
    use HasFactory, HasCacheManager;

    protected static $factory = \Database\Factories\General\CustomerFactory::class;

    protected $fillable = [
        'name',
        'phone_number',
        'department',
        'company_name',
        'company_address'
    ];

    public function urs()
    {
        return $this->hasMany(URS::class);
    }

    public static array $CACHE_KEYS = [
        'select_customer' => 'customer_select_default',
    ];

    public static array $CACHE_PREFIXES = [
        'search_customer' => 'customer_search_',
    ];

    protected static function booted()
    {
        static::saved(function () {
            static::clearModelCaches();
            Log::info("Customer cache cleared (saved)");
        });

        static::deleted(function () {
            static::clearModelCaches();
            Log::info("Customer cache cleared (deleted)");
        });
    }
}
