<?php

namespace App\Models\Sales;

use App\Models\General\Customer;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Traits\HasCacheManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class URS extends Model
{
    use HasFactory, HasCacheManager;
    protected $table = 'urs';
    protected $fillable = [
        'no_urs',
        'customer_id',
        'permintaan_khusus',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function spesifikasiProducts()
    {
        return $this->hasMany(SpesifikasiProduct::class, 'urs_id');
    }
}
