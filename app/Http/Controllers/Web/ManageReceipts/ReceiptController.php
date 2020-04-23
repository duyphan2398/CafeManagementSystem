<?php


namespace App\Http\Controllers\Web\ManageReceipts;


use App\Exports\ReceiptExport;
use App\Http\Controllers\WebBaseController;
use App\Models\Receipt;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends WebBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax){
            $receipts = Receipt::query()
                ->whereDate('created_at', '=', Carbon::today()->toDateString())
                ->orderByDesc('created_at')
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
}
