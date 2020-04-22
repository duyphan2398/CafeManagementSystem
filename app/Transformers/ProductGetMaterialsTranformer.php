<?php


namespace App\Transformers;


use App\Models\Material;
use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductGetMaterialsTranformer extends TransformerAbstract
{
    public function transform(Product $product)
    {   $data = [];
        foreach ( $product->materials as $material){

            array_push($data, [
                'material_id'   => (integer) $material->id,
                'material_name' => (string)     $material->name,
                'quantity'      => (float)   $material->pivot->quantity,
                'unit'          => (string)  $material->pivot->unit
            ]);
        }

        return $data;
    }

}
