<?php


namespace App\Transformers;


use App\Models\Material;

class MaterialTransformer
{
    public function transform(Material $material)
    {
        return [
            'id'                => $material->id,
            'name'              => $material->name,
            'amount'            => $material->amount,
            'unit'              => $material->unit,
            'note'              => $material->note,
            'updated_at'        => $material->updated_at,
            'created_at'        => $material->created_at
        ];
    }
}
