<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends ApiBaseController
{
    public function getSchdulesByUserId(){
        $user = Auth::guard('api')->user();
        return response()->json([
            'schedules' => $user->schedules,
            'message'    => 'success'
        ],200);
    }

    public function getSchdulesFilter(Request $request){

    }

}
