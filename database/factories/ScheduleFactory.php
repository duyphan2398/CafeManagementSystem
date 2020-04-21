<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;
use App\Models\Schedule;
use Faker\Generator as Faker;
use App\Models\User;
use Illuminate\Support\Arr;
$factory->define(Schedule::class, function (Faker $faker) {
    $start_time = Carbon::parse($faker->dateTimeBetween('05:00', '17:00'))->format('H:i');
    $end_time = Carbon::parse($faker->dateTimeBetween($start_time, $endDate = '23:00'))->format('H:i');
    $total_time =Carbon::parse($end_time)->floatDiffInHours($start_time);
    $checkin_time = Carbon::parse($start_time)->subMinutes(15);
    $checkout_time = Carbon::parse($end_time)->addMinutes(15);
    $date =  $faker->dateTimeBetween(Carbon::now()->subDays(30), Carbon::now()->addDays(30));
    return [
        'user_id'           =>  User::all()->random(1)->first()->id,
        'start_time'        => $start_time,
        'end_time'          => $end_time,
        'checkin_time'      =>($date < Carbon::today()) ? $checkin_time : null,
        'checkout_time'     =>($date < Carbon::today()) ? $checkout_time : null,
        'date'              =>$date,
        'total_time'        =>  $total_time
    ];
});
