<?php


namespace App\Transformers;


use App\Models\Receipt;

class ReceiptGetProductsTranformer
{
    public function transform(Receipt $receipt)
    {   $data = [];
        foreach ( $receipt->products as $product){
            array_push($data, [
                'receipt_id'                => (integer) $product->pivot->receipt_id,
                'product_id'                => (integer) $product->pivot->product_id,
                'product_name'              => (string)  $product->pivot->product_name,
                'quantity'                  => (integer) $product->pivot->quantity,
                'note'                      => (string)  $product->pivot->note,
                'product_price'             => (float)   $product->pivot->product_price,
                'product_sale_price'        => (float)   $product->pivot->product_sale_price,
            ]);
        }
        return $data;
    }
}
