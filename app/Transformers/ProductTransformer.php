<?php


namespace App\Transformers;


use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
     public function transform(Product $product)
    {
        return [
            'id'                => $product->id,
            'name'              => $product->name,
            'price'             => $product->price,
            'sale_price'        => $product->sale_price,
            'url'               => $product->url,
            'type'              => $product->type,
            'promotion_today'   => $product->promotion_today,
            'promotions'        => (new ProductGetPromotionsTransformer)->transform($product),
            'ingredients'       => (new ProductGetMaterialsTranformer)->transform($product)
        ];
    }
}
