<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;
use App\Models\Schedule;
use Faker\Generator as Faker;
use App\Models\User;
$factory->define(Schedule::class, function (Faker $faker) {
    $start_time = Carbon::parse($faker->dateTimeBetween('05:00', '17:00'))->format('H:i');
    $end_time = Carbon::parse($faker->dateTimeBetween($start_time, $endDate = '23:00'))->format('H:i');
    $total_time =Carbon::parse($end_time)->floatDiffInHours($start_time);
    return [
        'user_id' =>  User::all()->random(1)->first()->id,
        'start_time' => $start_time,
        'end_time' => $end_time,
        'date' => $faker->dateTimeBetween(now(), '2020-12-30'),
        'total_time' =>  $total_time
    ];
});
