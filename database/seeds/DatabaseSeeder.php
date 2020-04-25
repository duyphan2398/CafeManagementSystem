<?php

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(SchedulesTableSeeder::class);
         $this->call(MaterialsTableSeeder::class);
         $this->call(PromotionsTableSeeder::class);
         $this->call(ProductsTableSeeder::class);
         $this->call(TablesTableSeeder::class);
         $this->call(ReceiptsTableSeeder::class);
    }
}
