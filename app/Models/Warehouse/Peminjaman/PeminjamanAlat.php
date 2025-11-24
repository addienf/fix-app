<?php

namespace App\Models\Warehouse\Peminjaman;

use App\Models\Production\SPK\SPKVendor;
use App\Models\User;
use App\Models\Warehouse\Peminjaman\Pivot\PeminjamanAlatDetail;
use App\Models\Warehouse\Peminjaman\Pivot\PeminjamanAlatPIC;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanAlat extends Model
{
    use HasFactory;
    protected $fillable = [
        'spk_vendor_id',
        'tanggal_pinjam',
        'tanggal_kembali',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function spkVendor()
    {
        return $this->belongsTo(SPKVendor::class, 'spk_vendor_id');
    }

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'nama_peminjam');
    }

    public function serahTerimaBahan()
    {
        return $this->hasOne(SerahTerimaBahan::class, 'peminjaman_alat_id');
    }

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
