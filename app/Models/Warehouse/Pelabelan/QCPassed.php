<?php

namespace App\Models\Warehouse\Pelabelan;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Warehouse\Pelabelan\Pivot\QCPassedDetail;
use App\Models\Warehouse\Pelabelan\Pivot\QCPassedPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QCPassed extends Model
{
    use HasFactory;
    protected $table = 'qc_passeds';

    protected $fillable = [
        // 'spk_marketing_id',
        'pengecekan_performa_id',
        'tanggal',
        'penanggung_jawab',
        'total_masuk',
        'total_keluar',
        'sisa_stock',
        'status_persetujuan',
    ];

    // public function spk()
    // {
    //     return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    // }

    public function pengecekanPerforma()
    {
        return $this->belongsTo(PengecekanPerforma::class, 'pengecekan_performa_id');
    }

    public function details()
    {
        return $this->hasMany(QCPassedDetail::class, 'qc_passed_id');
    }

    public function pic()
    {
        return $this->hasOne(QCPassedPIC::class, 'qc_passed_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->approved_signature &&
                $model->status_persetujuan !== 'Disetujui'
            ) {
                $model->status_persetujuan = 'Disetujui';
            }
        });

        static::deleting(function ($model) {
            if ($model->detail) {
                $model->detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
