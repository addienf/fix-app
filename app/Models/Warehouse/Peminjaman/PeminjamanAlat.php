<?php

namespace App\Models\Warehouse\Peminjaman;

use App\Models\Warehouse\Peminjaman\Pivot\PeminjamanAlatDetail;
use App\Models\Warehouse\Peminjaman\Pivot\PeminjamanAlatPIC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanAlat extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_pinjam',
        'tanggal_kembali',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(PeminjamanAlatDetail::class);
    }

    public function pic()
    {
        return $this->hasOne(PeminjamanAlatPIC::class);
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
