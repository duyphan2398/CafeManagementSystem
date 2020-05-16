<?php


namespace App\Http\Controllers\Api;


use App\Events\ChangeStateTableEvent;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\TableUpdateProducts;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Schedule;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TableController extends ApiBaseController
{
    public function index(){
        $result = [];
        foreach (Table::all()->sortBy('name') as $table) {
            array_push($result, $table);
        }
        return response()->json([
            'tables'    => $result,
            'message'   =>'success'
        ],200);
    }

    public function show(Request $request, Table $table){
        $mineScheduleToday = Schedule::where('user_id', Auth::guard('api')->id())
            ->where('date', today()->format('Y-m-d'))->first();
        if ( $mineScheduleToday && $mineScheduleToday->checkin_time) {
            $receipt = Receipt::
            where('table_id', $table->id)
                ->whereIn('status', [1, 2])
                ->first();

            if ($table->user_id != null && $table->user_id != Auth::guard('api')->id()) {
                return response()->json([
                    'message' => 'Have orther user using !'
                ], 400);
            }

            $table->user_id = Auth::guard('api')->id();
            $table->save();
            event(new ChangeStateTableEvent('A table is chosen by a user'));
            if ($receipt) {
                return response()->json($this->result($request, $receipt, $table), 200);
            }
            return response()->json($this->result($request, $receipt, $table), 200);
        }
        else {
            return response()->json([
                'flag_checkin'  => false,
                'message'       => 'Not checkin yet!'
            ],400);
        }
    }


    //Update and Create Receipt
    public function updateProducts(TableUpdateProducts $request, Table $table){
        $receipt = Receipt::where('table_id', $table->id)
            ->whereIn('status', [1,2])
            ->first();
        if (!$receipt) {
            $receipt = new Receipt();
            $table->status = 'Using';
            $table->save();
            $receipt->fill([
                'table_id' => $table->id,
                'user_id'   => Auth::guard('api')->id()
            ]);
            $receipt->status = 1;
            $receipt->user_name = $receipt->user->name;
            $receipt->table_name = $receipt->table->name;
            $receipt->save();
        }
        $receipt->products()->detach();
        foreach ($request->product_list as $item){
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
        $result =  $this->result($request, $receipt, $table);
        $result['message'] = 'success';
        event(new ChangeStateTableEvent('A table updated'));
        return response()->json(
            $result
        ,201);
    }

    public function changeStateToNull(Request $request, Table $table){
        if ($table->user_id){
            $table->user_id = null;
        }
        else{
           return  response()->json([
               'messages' => 'Don\'t have anybody use this table !'
           ], 400);
        }
        if ($table->save()){
            event(new ChangeStateTableEvent('Change state User_using to null'));
            return response()->json([
                'messages'  =>'success'
            ],200);
        }
        return response()->json([
            'messages'  =>'fail to save the using user  to null'
        ],400);
    }
}
