<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Events\ChangeStateTableEvent;
use App\Events\ExportOrderFormEvent;
use App\Exports\ReceiptExport;
use App\Http\Controllers\WebBaseController;
use App\Models\Product;
use App\Models\Receipt;
use App\Transformers\ReceiptTranformer;
use Barryvdh\DomPDF\Facade as PDF2;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends WebBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax){
            $receipts = Receipt::query()
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->orderBy('updated_at', 'desc')
                ->get();
            return response()->json([
                'receipts' =>  $receipts
            ], 200);
        }
        return view('workspace.manageReceipts.receipt');
    }


    public function show(Request $request, Receipt $receipt){
        $drinks = $receipt->products()->where('type', 'Drink')->orderBy('name')->get();
        $drinks_orther = Product::whereDoesntHave('receipts',function($query) use ($receipt) {
            $query->where('receipt_id', $receipt->id);
        })->where('type', 'Drink')->orderBy('name')->get();
        $foods = $receipt->products()->where('type', 'Food')->orderBy('name')->get();
        $foods_orther = Product::whereDoesntHave('receipts',function($query) use ($receipt) {
            $query->where('receipt_id', $receipt->id);
        })->where('type', 'Food')->orderBy('name')->get();
        return response()->json([
            'drinks'    => $drinks->union($drinks_orther),
            'foods'     => $foods->union($foods_orther),
            'receipt'   => $receipt
        ], 200);
    }

    public function getListReceiptFillter(Request $request){
        $receipts = Receipt::query()
            ->orderBy('created_at');

        $receipts->when($request->from, function ($q) use (&$request){
            $q->where('created_at', '>=', Carbon::parse($request->from)->format('Y-m-d'));
        });
        $receipts->when($request->to, function ($q) use (&$request){
            $q->where('created_at', '<=', Carbon::parse($request->to)->format('Y-m-d'));
        });

        if ((count($receipts->get()))){
            return response()->json([
                'status'=> ' success',
                'receipts' => $receipts->get()
            ],200);
        }
        return response()->json([
            'status'=> 'Not found',
        ],404);

    }

    public function destroy(Receipt $receipt){
        DB::beginTransaction();
        try {
            $receipt->products()->detach();
            if ($receipt->status == 1 || $receipt->status == 2){
                $table = $receipt->table;
                $table->status = 'Empty';
                $table->save();
            }
            $receipt->delete();
            DB::commit();
            event(new ChangeStateTableEvent('A table is deleted'));
            return response()->json([
                'message' => 'success'
            ],200);

        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'message'   => 'fail'
            ], 404);
        }
    }

    public function exportReceiptCsv(Request $request){
        $from = $request->fromFillter;
        $to = $request->toFillter;
        return (new ReceiptExport($from, $to))->download('ReceiptList('.$from.'-To-'.$to.').csv', \Maatwebsite\Excel\Excel::CSV,  ['Content-Type' => 'text/csv']);
    }

    public function billing(Request $request, Receipt $receipt){
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
                    'host'    => '/export/pdf/bill/',
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

    public function receipt(Request $request, Receipt $receipt){
        if ($receipt->status == 2 || $receipt->status == 3 ){
            DB::beginTransaction();
            try {
                if ($receipt->status == 2) {
                    $receipt->receipt_at = Carbon::now();
                }
                $receipt->export_at = Carbon::now();
                $receipt->status = 3;
                //export PDF file
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
                    'host'    => '/export/pdf/paid/',
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

    public function updateProductInReceipt(Request $request, Receipt $receipt){
        //Export Order PDF
        $data = [];
        for ($i = 0; $i < count($request->products); $i++){
            $receipt_product = $receipt->products()
                ->where('receipt_id', $receipt->id)
                ->where('product_id', $request->products[$i]['id'])
                ->first();
            $product = Product::find($request->products[$i]['id']);
            // New record
            if (!$receipt_product) {
                $tmp_data = $request->products[$i];
                $tmp_data['type'] =  $product->type;
                $tmp_data['product_name'] =  $product->name;
                array_push($data, $tmp_data);
            }
            //Record existed
            else{
                $tmp_data = [];
                //Check quantity
                if ($receipt_product->pivot->quantity < $request->products[$i]['quantity']){
                    $tmp_data = [
                        'id'            => $request->products[$i]['id'],
                        'quantity'      => $request->products[$i]['quantity'] -  $receipt_product->pivot->quantity,
                        'note'          => $request->product_list[$i]['note'],
                        'type'          => $receipt_product->type,
                        'product_name'  =>  $product->name
                    ];
                    array_push($data, $tmp_data);
                }
                elseif ($receipt_product->pivot->quantity > $request->products[$i]['quantity']){
                    $tmp_data = [
                        'id'            => $request->products[$i]['id'],
                        'quantity'      => 'Cancle '.($request->products[$i]['quantity'] - $receipt_product->pivot->quantity),
                        'note'          => $request->products[$i]['note'],
                        'type'          => $receipt_product->type,
                        'product_name'  =>  $product->name
                    ];
                    array_push($data, $tmp_data);
                }

            }
        }

        /*Case Romove a product to 0*/
        foreach ($receipt->products as $product) {
            $proucts_id = array_column($request->products, 'id');
            $flag = in_array($product->id, $proucts_id);
            if (!$flag) {
                $tmp_data = [
                    'id'            => $product->id,
                    'quantity'      => 'Cancle All ( -'.($product->pivot->quantity).' )',
                    'note'          => '---Cancle All---'.$product->name,
                    'type'          => $product->type,
                    'product_name'  => $product->name
                ];
                array_push($data, $tmp_data);
            }
        }


        $pdf = PDF2::loadView('PDF.order', [
            'data'          => $data,
            'table'         => $receipt->table,
            'receipt'       => $receipt
        ]);
        $url = '\order\\';
        Storage::disk('public')->delete($url.$receipt->id.'.pdf');
        Storage::disk('public')->put($url.$receipt->id.'.pdf', $pdf->output());
        //Update Products
        $receipt->products()->detach();
        foreach ($request->products as $item){
            $product = Product::find($item['id']);
            DB::table('receipt_product')->insert([
                'receipt_id'            => $receipt->id,
                'product_id'            => $product->id,
                'quantity'              => $item['quantity'],
                'note'                  => $item['note'],
                'product_name'          => $product->name,
                'product_price'         => $product->price,
                'product_sale_price'    => $product->sale_price,
            ]);
        }
        $receipt->sale_excluded_price;
        $receipt->sale_included_price;
        $result =  $this->result($request, $receipt, $receipt->table);
        $result['message'] = 'success';
        //Realtime Event
        event(new ExportOrderFormEvent(
            $data,
            $this->result($request, $receipt, $receipt->table),
            $receipt->id.'.pdf',
            $request->getHttpHost().'/export/pdf/order/'
        ));
        event(new ChangeStateTableEvent('A table updated'));
        return response()->json([
            'data'          => $data,
            'result'        => $this->result($request, $receipt, $receipt->table),
            'url'           => $receipt->id.'.pdf',
            'host'          => '/export/pdf/order/',
            'receipt_id'    => $receipt->id,
        ],201);
    }
}
