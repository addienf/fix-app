<?php

namespace App\Models\Engineering\Maintenance\ColdRoom\Pivot;

use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ColdRoomPIC extends Model
{
    use HasFactory;

    protected $table = 'cold_room_pics';
    protected $fillable = [
        'cold_room_id',
        'checked_name',
        'checked_signature',
        'checked_date',
        'approved_name',
        'approved_signature',
        'approved_date',
    ];

    public function coldRoom()
    {
        return $this->belongsTo(ColdRoom::class, 'cold_room_id');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('checked_signature') &&
                $model->getOriginal('checked_signature') &&
                Storage::disk('public')->exists($model->getOriginal('checked_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('checked_signature'));
            }

            if (
                $model->isDirty('approved_signature') &&
                $model->getOriginal('approved_signature') &&
                Storage::disk('public')->exists($model->getOriginal('approved_signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('approved_signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->checked_signature && Storage::disk('public')->exists($model->checked_signature)) {
                Storage::disk('public')->delete($model->checked_signature);
            }

            if ($model->approved_signature && Storage::disk('public')->exists($model->approved_signature)) {
                Storage::disk('public')->delete($model->approved_signature);
            }
        });
    }
}
