<?php

namespace App\Models\MR\Perubahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerubahanInformasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal',
        'jenis_dokumen',
        'jenis_dokumen_lainnya',
        'perubahan_diminta',
        'status'
    ];

    public function dokPerubahan()
    {
        return $this->hasOne(DokumenPerubahan::class, 'perubahan_id');
    }

    public function persetujuanPerubahan()
    {
        return $this->hasOne(PersetujuanPerubahan::class, 'perubahan_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (
                $model->persetujuanPerubahan?->signature_manajemen &&
                $model->status !== 'Wakil Manajemen'
            ) {
                $model->status = 'Wakil Manajemen';
            }
        });

        static::deleting(function ($perubahan) {
            if ($perubahan->persetujuanPerubahan) {
                $perubahan->persetujuanPerubahan->delete();
            }
        });
    }
}
