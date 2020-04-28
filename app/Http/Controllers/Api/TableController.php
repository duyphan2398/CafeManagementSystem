<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\TableUpdateProducts;
use App\Models\Product;
use App\Models\Receipt;
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
        $receipt = Receipt::
            where('table_id', $table->id)
            ->whereIn('status', [1,2])
            ->first();
        if ($receipt){
            return response()->json($this->result($request, $receipt, $table),200);
        }
        return response()->json($this->result($request, $receipt, $table),200);
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
//---------------------- //Real time
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
        return response()->json([
            $result
        ],201);
    }

    public function changeState(Table $table){
        if ($table->user_id){
            $table->user_id = null;
        }
        else{
            $table->user_id = Auth::guard('api')->id();
        }
        if ($table->save()){
            return response()->json([
                'messages'  =>'success'
            ],200);
        }
        return response()->json([
            'messages'  =>'fail'
        ],200);
    }
}
