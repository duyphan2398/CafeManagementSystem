<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends ApiBaseController
{
    public function getSchdulesByUserId(Request $request){
        $user = Auth::guard('api')->user();

        $query = Schedule::query()->where('user_id', $user->id);

        $query->when($request->start_at, function ($query) use ($request) {
            return $query->where('date', '>=',Carbon::parse($request->start_at)->format('Y-m-d'));
        });

        $query->when($request->end_at, function ($query) use ($request) {
            return $query->where('date', '<=', Carbon::parse($request->end_at)->format('Y-m-d'));
        });

        return response()->json([
            'schedule_list'            => $query->get(),
            'message'             => 'success',
            'total_schedules'     => $query->count()
        ],200);
    }

}
