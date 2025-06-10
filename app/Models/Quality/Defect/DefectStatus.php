<?php

namespace App\Models\Quality\Defect;

use App\Models\Quality\Defect\Pivot\DefectStatusDetail;
use App\Models\Quality\Defect\Pivot\DefectStatusPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_marketing_id',
        'defectable_id',
        'defectable_type',
        'tipe',
        'volume',
        'serial_number',
        'note',
    ];

    public function spk()
    {
        return $this->belongsTo(SPKMarketing::class, 'spk_marketing_id');
    }

    public function defectable()
    {
        return $this->morphTo();
    }

    public function detail()
    {
        return $this->hasMany(DefectStatusDetail::class);
    }

    public function pic()
    {
        return $this->hasMany(DefectStatusPIC::class);
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            if ($model->detail) {
                $model->detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }

            if ($model->defectable) {
                $model->defectable->delete();
            }
        });
    }
}
