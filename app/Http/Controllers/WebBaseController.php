<?php


namespace App\Http\Controllers;


class WebBaseController extends Controller
{
    public function result($request, $receipt, $table, $flag_checkin = true){
        $result = [
            'flag_checkin'          => $flag_checkin,
            'receipt_id'            => null,
            'current_total'         => null,
            'current_sale_total'    => null,
            'receipt_status'        => null,
            'billing_at'            => null,
            'receipt_at'            => null,
            'export_at'             => null,
            'created_at'            =>null,
            'created_by_name'       =>null,
            'current_user_using'    => null,
            'host'                  => $request->getHttpHost().'/images/products/',
            'product_list'          => [],
        ];

        $receipt_product = [
            'id'            => null,
            'name'          => null,
            'price'         => null,
            'sale_price'    => null,
            'promotion_id'  => null,
            'quantity'      => null,
            'note'          => null,
            'type'          => null,
            'url'           => null,

        ];

        if ($receipt){
            $result['current_total']        = $receipt->sale_excluded_price;
            $result['current_sale_total']   = $receipt->sale_included_price;
            $result['receipt_id']           = $receipt->id;
            $result['current_user_using']   = $table->user_id;
            $result['created_by_name']      = $receipt->user->name;
            $result['created_at']           = $receipt->created_at;
            $result['billing_at']           = $receipt->billing_at;
            $result['receipt_at']           = $receipt->receipt_at;
            $result['export_at']            = $receipt->export_at;
            $result['receipt_status']       = $receipt->status;
            foreach ($receipt->products as $product){
                $receipt_product['id']          = $product->id;
                $receipt_product['name']        = $product->pivot->product_name;
                $receipt_product['price']       = $product->pivot->product_price;
                $receipt_product['sale_price']  = $product->pivot->product_sale_price;
                $receipt_product['promotion_id']= $product->promotion_id;
                $receipt_product['quantity']    = $product->pivot->quantity;
                $receipt_product['note']        = $product->pivot->note;
                $receipt_product['type']        = $product->type;
                $receipt_product['url']        = $product->url;
                array_push($result['product_list'], $receipt_product);
            }
        }
        return $result;
    }

}
