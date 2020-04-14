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
            $product = new Product([
                'name' => $faker->text(20),
                'price' =>$price,
                'sale_price' => $price*0.9,
                'type' => Arr::random(['Food','Drink']),
            ]);
            $product->save();
            $product->materials()->attach(
                Material::all()->random(3)
            );
        }
    }
}
