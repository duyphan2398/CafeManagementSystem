<?php


namespace App\Models;


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

    public function products(){
        return $this->hasMany(Product::class);
    }
}
