<?php

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use Carbon\Carbon;
class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Schedule::class, 100)->create();



        /*Create Schedules for User for testing*/


        $employee1 = \App\Models\User::query()->where('username', 'duyemployee')->first();
        $employee2 = \App\Models\User::query()->where('username', 'thuanemployee')->first();
        $manager1 = \App\Models\User::query()->where('username', 'duymanager')->first();
        $manager2 = \App\Models\User::query()->where('username', 'thuanmanager')->first();


        $date_start = new DateTime( Carbon::today()->subDay(14)->format('d-m-Y'));
        $date_end = new DateTime( Carbon::today()->addDay(14)->format('d-m-Y'));
        $date_array = new  DatePeriod($date_start, new DateInterval('P1D'), $date_end);
        $label = [];
        foreach ($date_array as $dt) {
            Schedule::query()->create($this->createSchedulesForTest($employee1, $dt->format('d-m-Y')));
            Schedule::query()->create($this->createSchedulesForTest($employee2, $dt->format('d-m-Y')));
            Schedule::query()->create($this->createSchedulesForTest($manager1, $dt->format('d-m-Y')));
            Schedule::query()->create($this->createSchedulesForTest($manager2, $dt->format('d-m-Y')));
        }
    }


    public function createSchedulesForTest(\App\Models\User $user, $date){
        $faker = \Faker\Factory::create();
        $start_time = Carbon::parse($faker->dateTimeBetween('05:00', '17:00'))->format('H:i');
        $end_time = Carbon::parse($faker->dateTimeBetween($start_time, $endDate = '23:00'))->format('H:i');
        $total_time =Carbon::parse($end_time)->floatDiffInHours($start_time);
        $checkin_time = Carbon::parse($start_time)->subMinutes(15);
        $checkout_time = Carbon::parse($end_time)->addMinutes(15);
        return [
            'user_id'           => $user->id,
            'start_time'        => $start_time,
            'end_time'          => $end_time,
            'checkin_time'      =>($date < Carbon::today()) ? $checkin_time : null,
            'checkout_time'     =>($date < Carbon::today()) ? $checkout_time : null,
            'date'              => $date,
            'total_time'        =>  $total_time
        ];
    }
}
