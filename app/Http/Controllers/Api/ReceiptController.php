<?php


namespace App\Http\Controllers\Api;


use App\Events\ChangeStateTableEvent;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\CreateProductReceiptRequest;
use App\Http\Requests\CreateReceiptRequest;
use App\Http\Requests\DeleteReceiptProductRequest;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Table;
use App\Transformers\ReceiptTranformer;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF2;
use Illuminate\Support\Facades\Storage;

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

    /*Method do not user*/
    public function create(CreateReceiptRequest $request){
        $table = Table::find($request->table_id);
        if ($table->status == 'Empty'){
            $table->status = 'Using';
            $table->save();
            //--------- //Real time
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

    /*Method do not user*/
    public function createProductReceipt(CreateProductReceiptRequest $request, Receipt $receipt, Product $product){
        if ($receipt->status == 1 || $receipt->status == 2){
            $receipt->products()->syncWithoutDetaching($product->id, [
                'product_price'         => $product->price,
                'product_sale_price'    => $product->sale_price,
                'product_name'          => $product->name,
                'quantity'              => $request->quantity,
                'note'                  => $request->note,
            ]);
            //------------- //Real time  Change price
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

    /*Method do not user*/
    public function destroy(Receipt $receipt){
        if ($receipt->status == 1){
            DB::beginTransaction();
            try {
                $receipt->products()->detach();
                $table = $receipt->table;
                $table->status = 'Empty';
                $table->save();
                //-------------- //Real time
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

    /*Method do not user*/
    public function destroyProductReceipt(Receipt $receipt, Product $product){
        if ($receipt->status == 1 || $receipt->status == 2){
            DB::beginTransaction();
            try {
                $receipt->products()->detach($product->id);
                //-----------------------------   // Real time
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

    /*Method do not user*/
    public function billReceipt(Request $request,Receipt $receipt){
        if ($receipt->status == 1 || $receipt->status == 2){
            DB::beginTransaction();
            try {
                $receipt->billing_at = Carbon::now();
                $receipt->status = 2;
                //in PDF kèm theo
                $pdf = PDF2::loadView('PDF.bill', ['receipt'=>(new \App\Transformers\ReceiptTranformer)->transform($receipt)]);
                $url = '\bill\\';
                Storage::disk('public')->delete($url.$receipt->id.'.pdf');
                Storage::disk('public')->put($url.$receipt->id.'.pdf', $pdf->output());
                $receipt->save();
                DB::commit();
                return response()->json([
                    'receipt' => (new ReceiptTranformer)->transform($receipt),
                    'bill'    => $receipt->id.'.pdf',
                    'host'    => $request->getHttpHost().'/export/pdf/bill/',
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
                'message' => 'The receipt has not suitable status - status 1 and 2 are accept to delete'
            ],400);
        }
    }

    /*Method do not user*/
    public function paidReceipt(Request $request, Receipt $receipt){
        if ($receipt->status == 2 || $receipt->status == 3 ){
            DB::beginTransaction();
            try {
                if ($receipt->status == 2) {
                    $receipt->receipt_at = Carbon::now();
                }
                $receipt->export_at = Carbon::now();
                $receipt->status = 3;
                //in PDF kèm theo
                $pdf = PDF2::loadView('PDF.paid', ['receipt'=>(new \App\Transformers\ReceiptTranformer)->transform($receipt)]);
                $url = '\paid\\';
                Storage::disk('public')->delete($url.$receipt->id.'.pdf');
                Storage::disk('public')->put($url.$receipt->id.'.pdf', $pdf->output());
                $table = $receipt->table;
                $table->status = 'Empty';
                $table->user_id = null;
                $receipt->save();
                $table->save();
                DB::commit();
                event(new ChangeStateTableEvent('A table is receipted'));
                return response()->json([
                    'receipt' => (new ReceiptTranformer)->transform($receipt),
                    'paid'    => $receipt->id.'.pdf',
                    'host'    => $request->getHttpHost().'/export/pdf/paid/',
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
                'message' => 'The receipt has not suitable status - status 2 and 3 are accept to delete'
            ],400);
        }
    }
}
