<?php


namespace App\Transformers;


use App\Models\Promotion;
use League\Fractal\TransformerAbstract;

class PromotionTransformer extends TransformerAbstract
{

    public function transform(Promotion $promotion)
    {
        return [
            'id'            => $promotion->id,
            'name'          => $promotion->name,
            'description'   => $promotion->description,
            'start_at'      => $promotion->start_at,
            'end_at'        => $promotion->end_at,
            'days'          => $promotion->days,
            'sale_percent'  => $promotion->sale_percent,
            'product_list'  => (new PromotionGetProductsTranformer)->transform($promotion)
        ];
    }
}
