<?php


namespace App\Transformers;


use App\Models\Promotion;
use League\Fractal\TransformerAbstract;

class PromotionGetProductsTranformer extends TransformerAbstract
{
    public function transform(Promotion $promotion)
    {   $data = [];
        foreach ( $promotion->products as $product){
            array_push($data, [
                'id'            => $product->id,
                'name'          => $product->name,
                'price'         => $product->price,
                'sale_price'    => $product->sale_price,
                'url'           => $product->url,
                'type'          => $product->type
            ]);
        }
        return $data;
    }
}
