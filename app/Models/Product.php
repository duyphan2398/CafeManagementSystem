<?php

namespace App\Models;

use App\Traits\ParseTimeStamp;
use Carbon\Carbon;
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
        'description',
    ];

    protected $attributes = [
        'url'           => 'default_url_product.png',
        'sale_price'    => null,
    ];

    public function getPriceAttribute($price){
        return round($price);
    }

    //Call sale price when get sale price
    public function getSalePriceAttribute(){
        if ($this->promotions()){
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            foreach ( $this->promotions as $promotion){
                $startDate = date('Y-m-d', strtotime($promotion->start_at));
                $endDate = date('Y-m-d', strtotime($promotion->end_at));
                if (($currentDate >= $startDate) && ($currentDate <= $endDate) &&(in_array(Carbon::parse($currentDate, 'UTC')->isoFormat('dddd'), explode( ',',str_replace('"','',$promotion->days))))){
                    return  round($this->price - ($promotion->sale_percent *  $this->price));
                    break;
                }
            }
            return null;
        }else {
            return null ;
        }

    }

    public function getPromotionTodayAttribute(){
        if ($this->promotions()){
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            foreach ( $this->promotions as $promotion){
                $startDate = date('Y-m-d', strtotime($promotion->start_at));
                $endDate = date('Y-m-d', strtotime($promotion->end_at));
                if (($currentDate >= $startDate) && ($currentDate <= $endDate) &&(in_array(Carbon::parse($currentDate, 'UTC')->isoFormat('dddd'), explode( ',',str_replace('"','',$promotion->days))))){
                    return $promotion;
                    break;
                }
            }
            return null;
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

    public function promotions(){
        return $this->belongsToMany(Promotion::class, 'product_promotion', 'product_id', 'promotion_id')->withTimestamps();
    }
}
