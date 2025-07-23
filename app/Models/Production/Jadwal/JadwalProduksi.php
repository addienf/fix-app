<?php

namespace App\Models\Production\Jadwal;

use App\Models\Production\Jadwal\Pivot\JadwalProduksiDetail as PivotJadwalProduksiDetail;
use App\Models\Production\Jadwal\Pivot\JadwalProduksiPIC;
use App\Models\Production\Jadwal\Pivot\SumberDaya as PivotSumberDaya;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $id
 * @property string|null $spk_marketing_id
 * @property string|null $tanggal
 * @property string|null $pic_name
 * @property string|null $status_persetujuan
 */
class JadwalProduksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_marketing_id',
        'tanggal',
        'pic_name',
        'status_persetujuan',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function details()
    {
        return $this->hasMany(PivotJadwalProduksiDetail::class);
    }

    public function pic()
    {
        return $this->hasOne(JadwalProduksiPIC::class);
    }

    public function sumber()
    {
        return $this->hasOne(PivotSumberDaya::class);
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->pic?->approve_signature &&
                $model->status_persetujuan !== 'Disetujui'
            ) {
                $model->status_persetujuan = 'Disetujui';
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->sumber) {
                $model->sumber->delete();
            }
        });
    }
}
