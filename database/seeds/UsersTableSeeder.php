<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 20)->create();
        DB::table('users')->insert([
            [   'name' => 'Duy Employee',
                'username'=>'duyemployee',
                'password' => Hash::make('123456789'),
                'role' => 'Employee',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Duy Manager',
                'username'=>'duymanager',
                'password' => Hash::make('123456789'),
                'role' => 'Manager',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Duy Admin',
                'username'=>'duyadmin',
                'password' => Hash::make('123456789'),
                'role' => 'Admin',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Thuan Employee',
                'username'=>'thuanemployee',
                'password' => Hash::make('123456789'),
                'role' => 'Employee',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Thuan Manager',
                'username'=>'thuanmanager',
                'password' => Hash::make('123456789'),
                'role' => 'Manager',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Thuan Admin',
                'username'=>'thuanadmin',
                'password' => Hash::make('123456789'),
                'role' => 'Admin',
                'remember_token' => Str::random(10)
            ]
        ]);
    }
}
