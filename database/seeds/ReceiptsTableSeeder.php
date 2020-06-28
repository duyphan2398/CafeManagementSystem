<?php

use Illuminate\Database\Seeder;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Product;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Arr;
class ReceiptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        //$faker->addProvider(new \Faker\Provider\DateTime());
        /*Status 3 : Paid*/
        for ($i = 1; $i <= 1000; $i++){
            $user = User::query()
                ->inRandomOrder()
                ->first();
            $table = Table::query()
                ->inRandomOrder()
                ->first();
            /*Date format*/
            $date =  $faker->dateTimeBetween(Carbon::now()->subYear(1), Carbon::now()->subMonth(3));
            $year = Carbon::parse($date)->year ;
            $month = Carbon::parse($date)->month;
            $date  = Carbon::parse($date)->day;
            $date_time = Carbon::create( $year, $month, $date, 12, 15, 00);
            /*-------------------------------------------*/
            $status = 3;
            $billing_at = $date_time->toDateTimeString();
            $date_time = $date_time->addMinutes(60)->toDateTimeString();
            $receipt_at = $date_time;
            $export_at = $receipt_at;
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
                'sale_excluded_price'   => null,
                'sale_included_price'   => null,
                'table_id'              => $table_id,
                'table_name'            => $table_name,
                'user_id'               => $user_id,
                'user_name'             => $user_name,
                'created_at'            => $billing_at
            ]);
            $receipt->save();
            /*------------------------------*/
            /*Product*/
            $products = Product::query()
                ->inRandomOrder()
                ->take(3)
                ->get();
            /*Need Fix when have promotion*/
            $array_products = [];
            $sale_excluded_price = 0;
            $sale_included_price = 0;

            foreach ($products as $product){
                $sale_percent = Arr::random([ 0.9, 0.8, 0.6, 0.5, 1, 1, 1, 1, 1]);
                $quantity = Arr::random([1,2,3]);
                $array_products[$product->id] = [
                    'receipt_id'        => $receipt->id,
                    'product_id'        => $product->id,
                    'quantity'          => $quantity,
                    'note'              => null,
                    'product_name'      => $product->name,
                    'product_price'     => $product->price,
                    'product_sale_price'=> $product->price*$sale_percent
                ];
                $sale_excluded_price += $product->price * $quantity;
                $sale_included_price += $product->price*$sale_percent*$quantity;
            }
            $receipt->products()->attach($array_products);
            $receipt->sale_excluded_price = $sale_excluded_price;
            $receipt->sale_included_price = $sale_included_price;
            $receipt->save();
        }


        /*Status 3 with right price*/
        for ($i = 1; $i <= 200; $i++){
            $user = User::query()
                ->inRandomOrder()
                ->first();
            $table = Table::query()
                ->inRandomOrder()
                ->first();
            /*Date format*/
            $date =  $faker->dateTimeBetween(Carbon::now()->subMonth(3), Carbon::now());
            $year = Carbon::parse($date)->year ;
            $month = Carbon::parse($date)->month;
            $date  = Carbon::parse($date)->day;
            $date_time = Carbon::create( $year, $month, $date, 12, 15, 00);
            /*-------------------------------------------*/
            $status = 3;
            $billing_at = $date_time->toDateTimeString();
            $date_time = $date_time->addMinutes(60)->toDateTimeString();
            $receipt_at = $date_time;
            $export_at = $receipt_at;
            $table_id = $table->id;
            $table_name = $table->name;
            $user_id = $user->id;
            $user_name = $user->name;

            $receipt = new Receipt();
            $receipt->fill([
                'status'                => 2,
                'billing_at'            => $billing_at,
                'receipt_at'            => $receipt_at,
                'export_at'             => $export_at,
                'sale_excluded_price'   => null,
                'sale_included_price'   => null,
                'table_id'              => $table_id,
                'table_name'            => $table_name,
                'user_id'               => $user_id,
                'user_name'             => $user_name,
                'created_at'            => $billing_at
            ]);
            $receipt->save();
            /*------------------------------*/
            /*Product*/
            $products = Product::query()
                ->inRandomOrder()
                ->take(3)
                ->get();
            /*Need Fix when have promotion*/
            $array_products = [];

            foreach ($products as $product){
                $quantity = Arr::random([1,2,3]);
                $array_products[$product->id] = [
                    'receipt_id'        => $receipt->id,
                    'product_id'        => $product->id,
                    'quantity'          => $quantity,
                    'note'              => null,
                    'product_name'      => $product->name,
                    'product_price'     => $product->price,
                    'product_sale_price'=> null
                ];
            }
            $receipt->products()->attach($array_products);
            $receipt->sale_excluded_price;
            $receipt->sale_included_price;
            $receipt->status = 3;
            $receipt->save();
        }


        /*Status 2 : Paid*/
        for ($i = 1; $i <= 3; $i++){
            $user = User::query()
                ->inRandomOrder()
                ->first();
            $table = Table::query()
                ->inRandomOrder()
                ->first();
            $table->status = 'Using';
            $table->user_id = Arr::random([$user->id, null]);
            $table->save();
            /*Date format*/
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $date  = Carbon::now()->day;
            $date_time = Carbon::now()->subMinute(rand(5,30));
            /*-------------------------------------------*/
            $status = 2;
            $billing_at = $date_time->toDateTimeString();
            $receipt_at = null;
            $export_at = null;
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
                'sale_excluded_price'   => null,
                'sale_included_price'   => null,
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
                ->take(3)
                ->get();
            /*Need Fix when have promotion*/
            $array_products = [];
            $sale_excluded_price = 0;
            $sale_included_price = 0;
            foreach ($products as $product){
                $quantity = Arr::random([1,2,3]);
                $array_products[$product->id] = [
                    'receipt_id'        => $receipt->id,
                    'product_id'        => $product->id,
                    'quantity'          => $quantity,
                    'note'              => null,
                    'product_name'      => $product->name,
                    'product_price'     => $product->price,
                    'product_sale_price'=> $product->sale_price ? $product->sale_price : $product->price
                ];
                $sale_excluded_price += $product->price * $quantity;
                $sale_included_price += ($product->sale_price) ? ($product->sale_price* $quantity) : ($product->price * $quantity);
            }
            $receipt->products()->attach($array_products);
            $receipt->sale_excluded_price = $sale_excluded_price;
            $receipt->sale_included_price = $sale_included_price;
            $receipt->save();
        }


        for ($i = 1; $i <= 4; $i++){
            $user = User::query()
                ->inRandomOrder()
                ->first();
            $table = Table::query()
                ->where('status', 'Empty')
                ->inRandomOrder()
                ->first();
            $table->status = 'Using';
            $table->user_id = null;
            $table->save();

            $status = 1;
            $billing_at = null;
            $receipt_at = null;
            $export_at = null;
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
                'sale_excluded_price'   => null,
                'sale_included_price'   => null,
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
                ->take(3)
                ->get();
            /*Need Fix when have promotion*/
            $array_products = [];
            $sale_excluded_price = 0;
            $sale_included_price = 0;
            foreach ($products as $product){
                $quantity = Arr::random([1,2,3]);
                $array_products[$product->id] = [
                    'receipt_id'        => $receipt->id,
                    'product_id'        => $product->id,
                    'quantity'          => $quantity,
                    'note'              => null,
                    'product_name'      => $product->name,
                    'product_price'     => $product->price,
                    'product_sale_price'=> $product->sale_price ? $product->sale_price : $product->price
                ];
                $sale_excluded_price += $product->price * $quantity;
                $sale_included_price += ($product->sale_price) ? ($product->sale_price*$quantity) : ($product->price * $quantity);
            }
            $receipt->products()->attach($array_products);
            $receipt->sale_excluded_price = $sale_excluded_price;
            $receipt->sale_included_price = $sale_included_price;
            $receipt->save();
        }
    }
}
