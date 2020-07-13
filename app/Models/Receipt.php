<?php


namespace App\Models;


use App\Traits\AddUser;
use App\Traits\ParseDateToRightFormat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;

class Receipt extends Model
{
    use AddUser;
    use ParseDateToRightFormat;
    use Exportable;
    public $timestamps = false;
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
        'user_name',
        'created_at',
        'updated_at'
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

    public function getSaleExcludedPriceAttribute(){
        if (in_array($this->status, [1,2])){
            $sale_excluded_price = 0;
            foreach ($this->products as $product){
                $this_sale_excluded_price = $product->price * $product->pivot->quantity;
                $sale_excluded_price += $this_sale_excluded_price;
                //Save price for this product pivot table of this receipt
                $product->pivot->product_price = $product->price;
                $product->pivot->save();
            }
            //Save price for this receipt
            $this->attributes['sale_excluded_price'] = round($sale_excluded_price);
            $this->save();
        }
        return $this->attributes['sale_excluded_price'];
    }

    public function getSaleIncludedPriceAttribute(){
        if (in_array($this->status, [1,2])){
            $sale_included_price = 0;
            foreach ($this->products as $product){
                $this_sale_included_price = ($product->sale_price ? $product->sale_price : $product->price) * $product->pivot->quantity;
                $sale_included_price += $this_sale_included_price;
                //Save sale price for this product pivot table of this receipt
                $product->pivot->product_sale_price =  $product->sale_price ? $product->sale_price : $product->price;
                $product->pivot->save();
            }
            //Save sale price for this receipt
            $this->attributes['sale_included_price'] = round($sale_included_price);
            $this->save();
        }
        return $this->attributes['sale_included_price'];

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
