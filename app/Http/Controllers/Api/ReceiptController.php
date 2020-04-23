<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\CreateProductReceiptRequest;
use App\Http\Requests\CreateReceiptRequest;
use App\Http\Requests\DeleteReceiptProductRequest;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Table;
use App\Transformers\ReceiptTranformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends ApiBaseController
{
    public function index(){
        $receipts = Receipt::query()
            ->whereDate('created_at', Carbon::today())
             ->get();
        $receipts_array = [];
        foreach ($receipts as $receipt){
            array_push($receipts_array, (new ReceiptTranformer)->transform($receipt));
        }
        return response()->json([
            'receipts'  => $receipts_array,
            'messages'  => 'success'
        ],200);
    }

    public function create(CreateReceiptRequest $request){
        $table = Table::find($request->table_id);
        if ($table->status == 'Empty'){
            $table->status = 'Using';
            $table->save();
//---------------------- //Real time
            $receipt = new Receipt();
            $receipt->fill($request->only(['table_id', 'user_id']));
            $receipt->status = 1;
            $receipt->user_name = $receipt->user->name;
            $receipt->table_name = $receipt->table->name;
            $receipt->save();
            return response()->json([
                'receipt' => (new ReceiptTranformer)->transform($receipt),
                'message'  => 'success'
            ],201);
        }
        return response()->json([
            'messages'  => 'fail - table is not empty'
        ],400);
    }

    public function createProductReceipt(CreateProductReceiptRequest $request, Receipt $receipt, Product $product){
        if ($receipt->status == 1 || $receipt->status == 2){
            $receipt->products()->syncWithoutDetaching($product->id, $request->only('note', 'quantity'));
//---------------------- //Real time  Change price
            return response()->json([
                'receipt' => (new ReceiptTranformer)->transform($receipt),
                'message'  => 'success'
            ],201);
        }
        return response()->json([
            'messages'  => 'fail - receipt status is 3 '
        ],400);
    }

    public function show(Receipt $receipt){
        return response()->json([
            'receipt' => (new ReceiptTranformer)->transform($receipt),
        ],200);
    }

    public function destroy(Receipt $receipt){
        if ($receipt->status == 1){
            DB::beginTransaction();
            try {
                $receipt->products()->detach();
                $table = $receipt->table;
                $table->status = 'Empty';
                $table->save();
//---------------------- //Real time
                $receipt->delete();
                DB::commit();
                return response()->json([
                    'message' => 'success'
                ],200);

            }catch (\Exception $exception){
                DB::rollBack();
                return response()->json([
                    'message'   => 'fail - server error'
                ], 404);
            }
        }
        else{
            return response()->json([
                'message' => 'The receipt has not suitable status - only status 1 is accept to delete'
            ],400);
        }
    }

    public function destroyProductReceipt(Receipt $receipt, Product $product){
        if ($receipt->status == 1 || $receipt->status == 2){
            DB::beginTransaction();
            try {
                $receipt->products()->detach($product->id);
//------------------------------------   //Change Price Real time
                DB::commit();
                return response()->json([
                    'receipt' => (new ReceiptTranformer)->transform($receipt),
                    'message' => 'success'
                ],200);

            }catch (\Exception $exception){
                DB::rollBack();
                return response()->json([
                    'message'   => 'fail - server error'
                ], 404);
            }
        }
        else{
            return response()->json([
                'message' => 'The receipt has not suitable status - only status 1 is accept to delete'
            ],400);
        }
    }
}
