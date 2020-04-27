<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;

class ProductController extends ApiBaseController
{
    public function index(Request $request){
        $products = Product::all();
        $result = [];
        foreach ($products as $product){
            array_push($result, (new ProductTransformer)->transform($product));
        }

        return response()->json([
            'product_list'    => $result,
            'host'            => $request->getHttpHost().'/images/products/',
            'message'         => 'success'
        ],200);
    }
}
