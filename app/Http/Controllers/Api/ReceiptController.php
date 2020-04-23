<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Receipt;
use App\Transformers\ReceiptTranformer;
use Carbon\Carbon;

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


}
