<?php


namespace App\Transformers;


use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
     public function transform(Product $product)
    {
        return [
            'id'            => $product->id,
            'name'          => $product->name,
            'price'         => $product->price,
            'sale_price'    => $product->sale_price,
            'url'           => $product->url,
            'type'          => $product->type,
            'ingredients'   => (new ProductGetMaterialsTranformer)->transform($product)
        ];
    }
}
