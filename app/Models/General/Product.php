<?php

namespace App\Models\General;

use App\Models\Sales\SpesifikasiProducts\Pivot\SpesifikasiProductDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function spesifikasiProductDetail()
    {
        return $this->hasMany(SpesifikasiProductDetail::class);
    }
}
