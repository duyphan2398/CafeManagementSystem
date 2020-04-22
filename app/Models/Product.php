<?php

namespace App\Models;

use App\Traits\ParseTimeStamp;
use Illuminate\Database\Eloquent\Model;

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

    protected $attributes = [
        'url'           => 'default_url_product.png',
        'sale_price'    => null
    ];
    // ======================================================================
    // Relationships
    // ======================================================================

    public function materials(){
        return $this->belongsToMany(Material::class, 'ingredients')->withPivot('quantity','unit');
    }
}
