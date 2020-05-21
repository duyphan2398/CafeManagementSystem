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
            'name'                  => 'Coffee in Summer 2020',
            'description'           => 'Discount 20% for the drinks from coffee bean in 2 - 4 - 6',
            'start_at'              => \Carbon\Carbon::today(),
            'end_at'                => Carbon\Carbon::today()->addMonth(),
            'days'                  => 'Monday,Wenesday,Friday,',
            'sale_percent'          => 0.20
        ]);

        DB::table('promotions')->insert([
            'name'                  => 'Thank Customers',
            'description'           => 'Discount 10% for the drinks best seller in 3 - 5 - 7',
            'start_at'              => \Carbon\Carbon::today(),
            'end_at'                =>\Carbon\Carbon::today()->addMonth(),
            'days'                  => 'Tuesday,Thursday,Saturday,',
            'sale_percent'          => 0.15
        ]);

        DB::table('promotions')->insert([
            'name'                  => 'My Cafe Birthday',
            'description'           => 'Discount 50% for the some drink',
            'start_at'              => \Carbon\Carbon::yesterday()->subMonth(),
            'end_at'                =>\Carbon\Carbon::yesterday(),
            'days'                  => 'Saturday,Sunday',
            'sale_percent'          => 0.5
        ]);

        DB::table('promotions')->insert([
            'name'                  => 'Rain and Coffee',
            'description'           => 'Discount 20% for some drinks',
            'start_at'              => \Carbon\Carbon::yesterday()->subMonth(),
            'end_at'                =>\Carbon\Carbon::yesterday(),
            'days'                  => 'Monday,Tuesday,Wenesday,Thursday,Friday,Saturday,Sunday',
            'sale_percent'          => 0.2
        ]);
    }
}
