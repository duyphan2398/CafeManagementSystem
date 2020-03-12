<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Models Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $role = ['Admin', 'Manager', 'Employee'];
    $arr = [null, now()];
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'password' => '123456789',
        'role' => Arr::random($role),
        'deleted_at' => Arr::random($arr),
        'remember_token' => Str::random(10),
    ];
});
