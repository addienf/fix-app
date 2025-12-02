<?php

namespace App\Models\Engineering\Service\Pivot;

use App\Models\Engineering\Service\ServiceReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReportProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'produk_name',
        'type',
        'serial_number',
        'status_warranty',
    ];

    public function service()
    {
        return $this->belongsTo(ServiceReport::class, 'service_id');
    }
}
