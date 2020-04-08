<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Models\Material;
use Illuminate\Support\Arr;
$factory->define(Material::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'amount' => $faker->numberBetween(10,200),
        'unit' => Arr::random(['KG', 'GRAM', 'PACKAGE', 'ML', 'L', 'PIECES'])
    ];
});
