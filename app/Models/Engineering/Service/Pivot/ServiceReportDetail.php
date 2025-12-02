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
        'remark',
        'taken_item',
        'service_status',
        'upload_file'
    ];

    protected $casts = [
        'upload_file' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(ServiceReport::class, 'service_id');
    }
}
