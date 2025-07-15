<?php

namespace App\Models\Engineering\Service\Pivot;

use App\Models\Engineering\Service\ServiceReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'taken_item',
        'status_service',
        'action',
        'service_fields',
        'upload_file',
    ];

    protected $casts = [
        'action' => 'array',
        'service_fields' => 'array',
        'upload_file' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(ServiceReport::class, 'service_id');
    }
}
