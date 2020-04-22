<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiBaseController
{
    public function index(Request $request){
        return response()->json([
            'products' => Product::all(),
            'host'     => $request->getHttpHost().'/images/products/',
            'message'  => 'success'
        ],200);
    }
}
