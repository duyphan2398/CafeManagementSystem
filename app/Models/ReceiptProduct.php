<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ReceiptProduct extends Pivot
{
    protected $table = 'receipt_product';

    protected $fillable = [
        'receipt_id',
        'product_id',
        'quantity',
        'note',
        'product_name',
        'product_price',
        'product_sale_price'
    ];
}
