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
        'type',
        'promotion_id',
    ];

    protected $attributes = [
        'url'           => 'default_url_product.png',
        'sale_price'    => null
    ];


    public function getSalePriceAttribute(){
        if ($this->promotion){
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            $startDate = date('Y-m-d', strtotime($this->promotion->start_at));
            $endDate = date('Y-m-d', strtotime($this->promotion->end_at));
            if ($this->promotion->sale_percent && ($currentDate >= $startDate) && ($currentDate <= $endDate)){
               return  $this->price - ($this->promotion->sale_percent *  $this->price);
            }
            else {
                return null ;
            }
        }else {
            return null ;
        }

    }
    // ======================================================================
    // Relationships
    // ======================================================================

    public function materials(){
        return $this->belongsToMany(Material::class, 'ingredients')->withPivot('quantity','unit');
    }

    public function receipts(){
        return $this
            ->belongsToMany(Receipt::class, 'receipt_product')
            ->using(ReceiptProduct::class)
            ->withPivot('receipt_id','product_name','quantity', 'note', 'product_name', 'product_price', 'product_sale_price');
    }

    public function promotion(){
        return $this->belongsTo(Promotion::class);
    }
}
