<?php

use Illuminate\Database\Seeder;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use Carbon\Carbon;
class ReceiptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::query()
            ->inRandomOrder()
            ->first();
        $table = Table::query()
            ->inRandomOrder()
            ->first();
        /*Date format*/
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $date  = Carbon::now()->day;
        $date_time = Carbon::create($year, $month - Arr::random([0,1]), rand(1,$date), 12, 15, 00);
        /*-------------------------------------------*/
        $status = 3;
        $billing_at = $date_time;
        $receipt_at = $date_time->subMinutes(60);
        $export_at = $receipt_at;
        //$sale_excluded_price = ;
        //$sale_included_price = ;
        $table_id = $table->id;
        $table_name = $table->name;
        $user_id = $user->id;
        $user_name = $user->name;

        $receipt = new Receipt();
        $receipt->fill([
            'status'                => $status,
            'billing_at'            => $billing_at,
            'receipt_at'            => $receipt_at,
            'export_at'             => $export_at,
            'sale_excluded_price'   => 1,
            'sale_included_price'   => 1,
            'table_id'              => $table_id,
            'table_name'            => $table_name,
            'user_id'               => $user_id,
            'user_name'             => $user_name
        ]);
        $receipt->save();
        /*------------------------------*/
        /*Product*/
        $products = Product::query()
            ->inRandomOrder()
            ->take(3);
        /*Need Fix when have promotion*/
        foreach ($products as $product){
            $receipt_product = new ReceiptProduct();
            $receipt_product->fill([
                'receipt_id'        => $receipt->id,
                'product_id'        => $product->id,
                'quantity'          =>Arr::random([1,2,3]),
                'note'              => null,
                'product_name'      =>$product->name,
                'product_price'     =>$product->price,
                'product_sale_price'=>$product->sale_price
            ]);
            $receipt_product->save();
        }
        /*------------------------------*/
    }
}
