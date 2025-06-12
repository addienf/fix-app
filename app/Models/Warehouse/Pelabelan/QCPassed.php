<?php

namespace App\Models\Warehouse\Pelabelan;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Pelabelan\Pivot\QCPassedDetail;
use App\Models\Warehouse\Pelabelan\Pivot\QCPassedPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QCPassed extends Model
{
    use HasFactory;
    protected $table = 'qc_passeds';

    protected $fillable = [
        'spk_marketing_id',
        'tanggal',
        'penanggung_jawab',
        'total_masuk',
        'total_keluar',
        'sisa_stock',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function detail()
    {
        return $this->hasOne(QCPassedDetail::class, 'qc_passed_id');
    }

    public function pic()
    {
        return $this->hasOne(QCPassedPIC::class, 'qc_passed_id');
    }

    // protected static function booted()
    // {
    //     static::deleting(function ($model) {
    //         if ($model->detail) {
    //             $model->detail->delete();
    //         }

    //         if ($model->pic) {
    //             $model->pic->delete();
    //         }
    //     });
    // }
}
