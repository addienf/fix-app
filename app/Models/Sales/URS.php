<?php

namespace App\Models\Sales;

use App\Models\General\Customer;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class URS extends Model
{
    use HasFactory;
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
        return $this->hasMany(SpesifikasiProduct::class);
    }
}
