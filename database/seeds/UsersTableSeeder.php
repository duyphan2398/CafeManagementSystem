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
        factory(User::class, 10)->create();
        DB::table('users')->insert([
            [   'name' => 'Duy Employee',
                'username'=>'duyemployee',
                'password' => Hash::make('123456789'),
                'role' => 'Employee',
                'email' => 'duyphan2398@gmail.com',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Duy Manager',
                'username'=>'duymanager',
                'password' => Hash::make('123456789'),
                'role' => 'Manager',
                'email' => 'duyphan2398@gmail.com',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Duy Admin',
                'username'=>'duyadmin',
                'password' => Hash::make('123456789'),
                'role' => 'Admin',
                'email' => 'duyphan2398@gmail.com',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Thuan Employee',
                'username'=>'thuanemployee',
                'password' => Hash::make('123456789'),
                'role' => 'Employee',
                'email' => 'dinhthuan2020@gmail.com',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Thuan Manager',
                'username'=>'thuanmanager',
                'password' => Hash::make('123456789'),
                'role' => 'Manager',
                'email' => 'dinhthuan2020@gmail.com',
                'remember_token' => Str::random(10)
            ],
            [   'name' => 'Thuan Admin',
                'username'=>'thuanadmin',
                'email' => 'dinhthuan2020@gmail.com',
                'password' => Hash::make('123456789'),
                'role' => 'Admin',
                'remember_token' => Str::random(10)
            ]
        ]);
    }
}
