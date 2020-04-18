<?php

namespace App\Models;

use App\Traits\ParseTimeStamp;
use Illuminate\Database\Eloquent\Model;
use App\Models\Material;

class Product extends Model
{
    use ParseTimeStamp;

    protected $fillable = [
        'name',
        'price',
        'sale_price',
        'url',
        'type'
    ];

    // ======================================================================
    // Relationships
    // ======================================================================

    public function materials(){
        return $this->belongsToMany(Material::class, 'ingredients')->withPivot('quantity','unit');
    }
}
