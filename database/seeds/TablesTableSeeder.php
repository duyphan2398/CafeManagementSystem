<?php

use Illuminate\Database\Seeder;
use App\Models\Table;

class TablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Table::create([
            'name'  => 'A1',
            'note'  => 'The left top table'
        ]);
        Table::create([
            'name'  => 'A2',
            'note'  => 'The between top table'
        ]);
        Table::create([
            'name'  => 'A3',
            'note'  => 'The right top table'
        ]);
        Table::create([
            'name'  => 'B1',
            'note'  => 'The left bottom table'
        ]);
        Table::create([
            'name'  => 'B2',
            'note'  => 'The between bottom table'
        ]);
        Table::create([
            'name'  => 'B3',
            'note'  => 'The right bottom table'
        ]);
    }
}
