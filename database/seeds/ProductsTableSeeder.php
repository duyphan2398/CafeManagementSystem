<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Product;
use App\Models\Material;
class ProductsTableSeeder extends Seeder
{
    protected $total = 50;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));

        for ($i = 0; $i < $this->total; $i++) {
            $price = Arr::random([50000,45000,65000,35000,20000,25000,40000,30000,55000,60000]);
            $type = Arr::random(['Food','Drink']);
            //Check unique of product name
            $flag = true;



            while ($flag)
            {
                $name = ($type == 'Food') ? Arr::random([$faker->foodName(),$faker->meatName(), $faker->dairyName()]) : Arr::random([$faker->beverageName(), $faker->fruitName(), $faker->vegetableName()]);
                if (Product::where('name', $name)->exists()){
                    $flag = true;
                }
                else {
                    $flag = false;
                }
            }
            //Create
            $product = new Product([
                'name'          => $name,
                'price'         => $price,
                'sale_price'    => null,
                'type'          => $type,
                'description'   => $faker->paragraph($nbSentences = 1, $variableNbSentences = true)
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

            if (rand(0,1) == 0) {
                $product->promotions()->save(\App\Models\Promotion::query()->inRandomOrder()->first());
            }
        }
    }
}
