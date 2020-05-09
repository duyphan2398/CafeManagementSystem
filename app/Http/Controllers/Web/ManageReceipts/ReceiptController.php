<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Events\ChangeStateTableEvent;
use App\Exports\ReceiptExport;
use App\Http\Controllers\WebBaseController;
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
                ->orderBy('status')
                ->get();
            return response()->json([
                'receipts' =>  $receipts
            ], 200);
        }
        return view('workspace.manageReceipts.receipt');
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
}
