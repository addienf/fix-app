<?php

namespace App\Models\Engineering\Complain\Pivot;

use App\Models\Engineering\Complain\Complain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'complain_id',
        'unit_name',
        'tipe_model',
        'status_warranty',
        'field_category',
        'deskripsi',
    ];

    public function complain()
    {
        return $this->hasMany(Complain::class, 'complain_id');
    }
}
