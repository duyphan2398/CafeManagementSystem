<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promotions')->insert([
            'name' => 'Coffee in Summer 2020',
            'description' => 'Discount 15% for the drinks from coffee bean',
            'start_at' => \Carbon\Carbon::today(),
            'end_at' =>Carbon\Carbon::today()->addMonth(),
            'sale_percent' => 0.15
        ]);

        DB::table('promotions')->insert([
            'name' => 'Thank Customers',
            'description' => 'Discount 10% for the drinks best seller',
            'start_at' => \Carbon\Carbon::today(),
            'end_at' =>\Carbon\Carbon::today()->addMonth(),
            'sale_percent' => 0.1
        ]);

        DB::table('promotions')->insert([
            'name' => 'My Cafe Birthday',
            'description' => 'Discount 50% for the some drink',
            'start_at' => \Carbon\Carbon::yesterday()->subMonth(),
            'end_at' =>\Carbon\Carbon::yesterday(),
            'sale_percent' => 0.5
        ]);
    }
}
