<?php

namespace App\Models\Warehouse\Peminjaman\Pivot;

use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PeminjamanAlatPIC extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_alat_pics';

    protected $fillable = [
        'peminjaman_alat_id',
        'department',
        'nama_peminjam',
        'signature',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanAlat::class);
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('signature') &&
                $model->getOriginal('signature') &&
                Storage::disk('public')->exists($model->getOriginal('signature'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('signature'));
            }
        });

        static::deleting(function ($model) {
            if ($model->signature && Storage::disk('public')->exists($model->signature)) {
                Storage::disk('public')->delete($model->signature);
            }
        });
    }
}
