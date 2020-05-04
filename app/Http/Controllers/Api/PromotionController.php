<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Promotion;
use App\Transformers\PromotionTransformer;
use Illuminate\Http\Request;

class PromotionController extends ApiBaseController
{
    public function index(){
        return response()->json([
            'promotion_list' => Promotion::all()
        ],200);
    }

    public function show(Request $request, Promotion $promotion){
        return response()->json((new PromotionTransformer)->transform($promotion),200);
    }
}
