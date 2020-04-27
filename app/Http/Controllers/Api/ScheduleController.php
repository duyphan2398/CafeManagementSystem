<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Schedule;
use App\Models\User;

class ScheduleController extends ApiBaseController
{
    public function getSchdulesByUserId(User $user){
        return response()->json([
            'schedules' => $user->schedules,
            'message'    => 'success'
        ],200);
    }
}
