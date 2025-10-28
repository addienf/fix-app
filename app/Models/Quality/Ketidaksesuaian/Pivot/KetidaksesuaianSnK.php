<?php

namespace App\Models\Quality\Ketidaksesuaian\Pivot;

use App\Models\Quality\Ketidaksesuaian\Ketidaksesuaian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetidaksesuaianSnK extends Model
{
    use HasFactory;

    protected $table = 'ketidaksesuaian_snks';

    protected $fillable = [
        'ketidaksesuaian_id',
        'penyebab',
        'tindakan_kolektif',
        'tindakan_pencegahan',
    ];

    public function ketidaksesuaian()
    {
        return $this->belongsTo(Ketidaksesuaian::class, 'ketidaksesuaian_id');
    }
}
