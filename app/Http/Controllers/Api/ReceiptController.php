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
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF2;
use Illuminate\Support\Facades\File;
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

    //get billing_at
    public function billReceipt(Request $request,Receipt $receipt){
        if ($receipt->status == 1 || $receipt->status == 2){
            DB::beginTransaction();
            try {
                $receipt->billing_at = Carbon::now();
                $receipt->status = 2;
                //in PDF kèm theo
                $pdf = PDF2::loadView('PDF.bill', ['receipt'=>(new \App\Transformers\ReceiptTranformer)->transform($receipt)]);
                $url = 'public\export\pdf\bill\\';
                Storage::delete($url.$receipt->id.'.pdf');
                Storage::put($url.$receipt->id.'.pdf', $pdf->output());
//------------------------------------   // Real time
                $receipt->save();
                DB::commit();
                return response()->json([
                    'receipt' => (new ReceiptTranformer)->transform($receipt),
                    'bill'    => $receipt->id.'.pdf',
                    'host'    => $request->getHttpHost().'/storage/export/pdf/bill/',
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

    //get receipt_at
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
                $url = 'public\export\pdf\paid\\';
                Storage::delete($url.$receipt->id.'.pdf');
                Storage::put($url.$receipt->id.'.pdf', $pdf->output());
//------------------------------------   // Real time
                $table = $receipt->table;
                $table->status = 'Empty';
                $receipt->save();
                $table->save();
                DB::commit();
                return response()->json([
                    'receipt' => (new ReceiptTranformer)->transform($receipt),
                    'paid'    => $receipt->id.'.pdf',
                    'host'    => $request->getHttpHost().'/storage/export/pdf/paid/',
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
