<?php

namespace App\Models\Sales\SpesifikasiProducts;

use App\Models\General\Customer;
use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductDetail;
use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductPIC;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Sales\URS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property string|null $id
 * @property string|null $urs_id
 * @property string|null $is_stock
 * @property string|null $detail_specification
 * @property string|null $delivery_address
 */
class SpesifikasiProduct extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'urs_id',
        'is_stock',
        'detail_specification',
        'delivery_address',
        'estimasi_pengiriman',
        'status_penerimaan_order',
        'alasan',
        'status'
    ];

    protected $casts = [
        'estimasi_pengiriman' => 'date',
    ];

    protected $with = ['urs', 'pic'];

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

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, URS::class, 'id', 'id', 'urs_id', 'customer_id');
    }

    public function getSelectCacheConfig(): array
    {
        return [
            [
                'relation' => 'urs',
                'title' => 'no_urs',
                'limit' => 10,
            ],
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->relationLoaded('pic') && $model->pic) {
                if ($model->pic->accepted_signature && $model->status !== 'Diterima') {
                    $model->status = 'Diterima';
                } elseif ($model->pic->acknowledge_signature && $model->status !== 'Diketahui MR') {
                    $model->status = 'Diketahui MR';
                }
            }
        });

        static::deleting(function ($model) {
            foreach ($model->details as $detail) {
                $detail->delete();
            }

            $model->pic?->delete();
        });
    }
}
