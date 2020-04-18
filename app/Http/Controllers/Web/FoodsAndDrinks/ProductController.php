<?php


namespace App\Http\Controllers\Web\FoodsAndDrinks;

use App\Http\Controllers\WebBaseController;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ProductController extends WebBaseController
{

    public function index(Request $request)
    {
        if ($request->ajax){

            $drinks_product = Product::where('type', 'Drink')
                ->orderBy('created_at', 'desc')
                ->get();
            $drinks_array = [];
            foreach ($drinks_product as $drink_product) {
                 array_push($drinks_array, (new ProductTransformer)->transform($drink_product));
            }
            $foods_product = Product::where('type','Food')
                ->orderBy('created_at', 'desc')
                ->get();
            $foods_array = [];
            foreach ($foods_product as $food_product) {
                 array_push($foods_array, (new ProductTransformer)->transform($food_product));
            }
            return response()->json([
                'drinks' =>  $drinks_array,
                'foods' =>   $foods_array,
            ], 200);
        }
        return view('workspace.foodsAndDrinks.product');
    }

    public function show(Product $product){
        return response()->json([
            'product' =>  (new ProductTransformer)->transform($product),
        ], 200);
    }

    public function update(Request $request){
        return $request;
    }
}
