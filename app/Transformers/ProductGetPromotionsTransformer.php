<?php


namespace App\Transformers;


use App\Models\Product;
use App\Models\Promotion;
use League\Fractal\TransformerAbstract;

class ProductGetPromotionsTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {   $data = [];
        foreach ( $product->promotions as $promotion) {
            array_push($data, [
                'id' => $promotion->id,
                'name' => $promotion->name,
                'description' => $promotion->description,
                'start_at' => $promotion->start_at,
                'end_at' => $promotion->end_at,
                'days' => $promotion->days,
                'sale_percent' => $promotion->sale_percent
            ]);
        }
        return($data);
    }
}
