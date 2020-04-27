<?php


namespace App\Models;


use App\Traits\AddUser;
use App\Traits\ParseTimeStamp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;

class Receipt extends Model
{
    use AddUser;
    use ParseTimeStamp;
    use Exportable;
    /*Status is [

        giá trị 1 : Wait for pay -> chờ thanh toán (trường hợp order đang mở billing_at và receipt_at null)
        giá trị 2 : Unpaid  ->chưa thanh toán (trường hợp in tạm tính  billing_at)
        giá trị 3 : Paid -> đã thanh toán  (trường hợp đóng bill receipt_at)

    ]*/
    protected $fillable = [
        'status',
        'billing_at',
        'receipt_at',
        'export_at',
        'sale_excluded_price',
        'sale_included_price',
        'table_id',
        'table_name',
        'user_id',
        'user_name'
    ];
    public function getBillingAtAttribute($billing_at){
        return ($billing_at) ? Carbon::parse($billing_at)->format('H:i d-m-Y') : $billing_at;
    }
    public function getReceiptAtAttribute($receipt_at){
        return ($receipt_at) ? Carbon::parse($receipt_at)->format('H:i d-m-Y') : $receipt_at;
    }
    public function getExportAtAttribute($export_at){
        return ($export_at) ? Carbon::parse($export_at)->format('H:i d-m-Y') : $export_at;
    }

    public function getPriceExcludedSaleAttribute($price_excluded_sale){
        $price_excluded_sale = 0;
        foreach ($this->products() as $product){
            $price_excluded_sale += $product->pivot->product_price * $product->pivot->quantity;
         }
        return round($price_excluded_sale);
    }

    public function getPriceIncludedSaleAttribute($price_included_sale){
        $price_included_sale = 0;
        foreach ($this->products() as $product){
            $price_included_sale += ($product->pivot->product_sale_price) ? ($product->pivot->product_sale_price * $product->pivot->quantity) : $product->pivot->product_price * $product->pivot->quantity;
        }
        return round($price_included_sale);
    }
    // ======================================================================
    // Relationships
    // ======================================================================

    public function products(){
        return $this
            ->belongsToMany(Product::class, 'receipt_product')
            ->using(ReceiptProduct::class)
            ->withPivot('receipt_id','product_name','quantity', 'note', 'product_name', 'product_price', 'product_sale_price');;
    }

    public function table(){
        return $this->belongsTo(Table::class);
    }
}
