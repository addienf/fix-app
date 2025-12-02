<?php

namespace App\Models\Production\Penyerahan\PenyerahanElectrical\Pivot;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaElectrical extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyerahan_electrical_id',
        'tanggal',
        'diterima_oleh',
        'catatan_tambahan',
        'status_penerimaan',
        'penjelasan_status',
        'alasan_status'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function penyerahanElectrical()
    {
        return $this->belongsTo(PenyerahanElectrical::class, 'penyerahan_electrical_id');
    }
}
