<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_at',
        'end_at',
        'sale_percent'
    ];

    public function getStartAtAttribute($start_at){
        return Carbon::parse($start_at)->format('d-m-Y');
    }
    public function getEndAtAttribute($end_at){
        return Carbon::parse($end_at)->format('d-m-Y');
    }

    public function products(){
    return $this->belongsToMany(Product::class, 'product_promotion', 'promotion_id', 'product_id')->withTimestamps();
    }
}
