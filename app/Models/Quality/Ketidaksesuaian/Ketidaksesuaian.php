<?php

namespace App\Models\Quality\Ketidaksesuaian;

use App\Models\Quality\Ketidaksesuaian\Pivot\KetidaksesuaianDetail;
use App\Models\Quality\Ketidaksesuaian\Pivot\KetidaksesuaianPIC;
use App\Models\Quality\Ketidaksesuaian\Pivot\KetidaksesuaianSnK;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketidaksesuaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengecekan_performa_id',
        'tanggal',
        'nama_perusahaan',
        'department',
        'pelapor',
        'status',
    ];

    public function pengecekanPerforma()
    {
        return $this->belongsTo(SPKMarketing::class, 'pengecekan_performa_id');
    }

    public function pic()
    {
        return $this->hasOne(KetidaksesuaianPIC::class, 'ketidaksesuaian_id');
    }

    public function details()
    {
        return $this->hasMany(KetidaksesuaianDetail::class, 'ketidaksesuaian_id');
    }

    public function snk()
    {
        return $this->hasOne(KetidaksesuaianSnK::class, 'ketidaksesuaian_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->diterima_signature &&
                $model->status !== 'Diterima'
            ) {
                $model->status = 'Diterima';
            }
        });

        static::deleting(function ($model) {
            if ($model->details) {
                foreach ($model->details as $detail) {
                    $detail->delete();
                }
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->snk) {
                $model->snk->delete();
            }
        });
    }
}
