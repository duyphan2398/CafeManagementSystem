<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Models\Material;
use Illuminate\Support\Arr;
$factory->define(Material::class, function (Faker $faker) {
    $faker = \Faker\Factory::create();
    $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
    return [
        'name' => Arr::random([$faker->fruitName(), $faker->vegetableName(), $faker->dairyName()]),
        'amount' => $faker->numberBetween(10,200),
        'unit' => Arr::random(['KG', 'GRAM', 'PACKAGE', 'ML', 'L', 'PIECES'])
    ];
});
