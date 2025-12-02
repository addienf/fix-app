<?php

namespace App\Models\Engineering\Berita\Pivot;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraPIC extends Model
{
    use HasFactory;

    protected $table = 'berita_acara_pics';

    protected $fillable = [
        'berita_id',
        'jasa_name',
        'jasa_ttd',
    ];

    public function beritaAcara()
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_id');
    }

    public function jasaName()
    {
        return $this->belongsTo(User::class, 'jasa_name');
    }

    protected static function booted(): void
    {
        static::updating(function ($model) {
            if (
                $model->isDirty('jasa_ttd') &&
                $model->getOriginal('jasa_ttd') &&
                Storage::disk('public')->exists($model->getOriginal('jasa_ttd'))
            ) {
                Storage::disk('public')->delete($model->getOriginal('jasa_ttd'));
            }
        });

        static::deleting(function ($model) {
            if ($model->jasa_ttd && Storage::disk('public')->exists($model->jasa_ttd)) {
                Storage::disk('public')->delete($model->jasa_ttd);
            }
        });
    }
}
