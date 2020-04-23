<?php


namespace App\Transformers;


use App\Models\Receipt;
use League\Fractal\TransformerAbstract;

class ReceiptTranformer extends  TransformerAbstract
{

    public function transform(Receipt $receipt)
    {
        return [
            'id'                        => $receipt->id,
            'status'                    => $receipt->status,
            'billing_at'                => $receipt->billing_at,
            'receipt_at'                => $receipt->receipt_at,
            'export_at'                 => $receipt->export_at,
            'sale_excluded_price'       => $receipt->sale_excluded_price,
            'sale_included_price'       => $receipt->sale_included_price,
            'table_id'                  => $receipt->table_id,
            'table_name'                => $receipt->table_name,
            'user_id'                   => $receipt->user_id,
            'user_name'                 => $receipt->user_name,
            'created_at'                => $receipt->created_at,
            'products'                  => (new ReceiptGetProductsTranformer)->transform($receipt),
        ];
    }
}
