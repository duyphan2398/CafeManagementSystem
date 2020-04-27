<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Product;
use App\Models\Material;
class ProductsTableSeeder extends Seeder
{
    protected $total = 20;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $this->total; $i++) {
            $price = $faker->numberBetween(10000,50000);
            //$sale_price = $price * Arr::random([ 0.9, 0.8, 0.5, 1 ,1 ,1 ]);
            $product = new Product([
                'name' => $faker->text(20),
                'price' =>$price,
                //'sale_price' => ( $sale_price == $price) ? null: round($sale_price),
                'sale_price' => null,
                'promotion_id' => Arr::random([null, null, 1, 1, 2, 2, 3]),
                'type' => Arr::random(['Food','Drink']),
            ]);
            $product->save();

            $materials = Material::query()
                ->inRandomOrder()
                ->take(4)
                ->get();

            foreach ($materials as $material){
                \Illuminate\Support\Facades\DB::table('ingredients')->insert([
                    'material_id' => $material->id,
                    'product_id'=> $product->id,
                    'quantity' => $material->amount * 0.05,
                    'unit' => $material->unit
                ]);
            }
        }
    }
}
