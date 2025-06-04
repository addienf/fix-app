<?php

namespace App\Models\Sales\SpesifikasiProducts;

use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductDetail;
use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Sales\URS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SpesifikasiProduct extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'urs_id',
        'is_stock',
        'detail_specification',
        'delivery_address',
    ];

    public function urs()
    {
        return $this->belongsTo(URS::class);
    }

    public function details()
    {
        return $this->hasMany(SpesifikasiProductDetail::class);
    }

    public function pic()
    {
        return $this->hasOne(SpesifikasiProductPIC::class);
    }

    public function spk()
    {
        return $this->hasOne(SPKMarketing::class);
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            if ($model->pic) {
                $model->pic->delete();
            }
        });
    }
}
