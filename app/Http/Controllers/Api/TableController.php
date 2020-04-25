<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\TableUpdateProducts;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class TableController extends ApiBaseController
{
    public function index(){
        return response()->json([
            'tables'    => Table::all()->sortBy('name'),
            'message'   =>'success'
        ],200);
    }

    public function show(Table $table){
        $result = [
            'current_total'          => null,
            'current_sale_total'    => null,
            'receipt_id'            => null,
            'product_list'          => [],
            'current_user_using'    => null,
            'created_at'            =>null,
            'created_by_name'       =>null
        ];

        $receipt_product = [
            'id'            => null,
            'name'          => null,
            'price'         => null,
            'sale_price'    => null,
            'quantity'      => null,
            'note'          => null,
            'type'          => null
        ];
        $receipt = Receipt::
            where('table_id', $table->id)
            ->whereIn('status', [1,2])
            ->first();
         ;
        if ($receipt){
            $result['current_total']        = $receipt->sale_excluded_price;
            $result['current_sale_total']   = $receipt->sale_included_price;
            $result['receipt_id']           = $receipt->id;
            $result['current_user_using']   = $table->user_id;
            $result['created_by_name']      = $receipt->user->name;
            $result['created_at']           = $receipt->created_at;
            foreach ($receipt->products as $product){
                $receipt_product['id']          = $product->id;
                $receipt_product['name']        = $product->pivot->product_name;
                $receipt_product['price']       = $product->pivot->product_price;
                $receipt_product['sale_price']  = $product->pivot->product_sale_price;
                $receipt_product['quantity']            = $product->pivot->quantity;
                $receipt_product['note']                = $product->pivot->note;
                $receipt_product['type']        = $product->type;
                array_push($result['product_list'], $receipt_product);
            }
            return response()->json($result,200);
        }
        return response()->json($result,200);
    }

    public function updateProducts(TableUpdateProducts $request, Table $table){
        $receipt = Receipt::where('table_id', $table->id)
            ->whereIn('status', [1,2])
            ->first();
        if ($receipt){
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
            return response()->json([
                'message' => 'success'
            ],201);
        }
        return response()->json([
            'message' => 'Not found receipt'
        ],404);
    }
}
