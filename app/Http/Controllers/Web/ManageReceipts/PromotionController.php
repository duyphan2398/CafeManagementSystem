<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Http\Controllers\WebBaseController;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionController extends WebBaseController
{
    public function index(Request $request){
        if ($request->ajax) {
            return response()->json([
                'promotions' => Promotion::all(),
                'messages'   => 'success'
            ],200);
        }
        return view('workspace.manageReceipts.promotion');
    }

    public function destroy(Promotion $promotion){
        DB::beginTransaction();
        try {
            Product::where('promotion_id', $promotion->id)->update([
                'promotion_id' => null
            ]);
            $promotion->delete();

            DB::commit();
            return response([
                'status' => 'success'
            ],200);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response([
                'status' => 'fail'
            ],400);
        }

    }
}
