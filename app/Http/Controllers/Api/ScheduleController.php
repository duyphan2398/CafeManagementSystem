<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiBaseController;
use App\Models\Schedule;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ScheduleController extends ApiBaseController
{
    public function getSchdulesByUserId(Request $request){
        $user = Auth::guard('api')->user();

        /*Count date from start to end*/
        $from = strtotime($request->start_at);
        $to = strtotime($request->end_at);
        $start = new DateTime(date('Y-m-d', $from));
        $end = new DateTime(date('Y-m-d', $to));
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $label[] = $dt->format('d-m-Y');
        }


        /*Query Schedule*/
        /*$query = Schedule::query()->where('user_id', $user->id);
        $query->when($request->start_at, function ($query) use ($request) {
            return $query->where('date', '>=',Carbon::parse($request->start_at)->format('Y-m-d'));
        });
        $query->when($request->end_at, function ($query) use ($request) {
           return $query->where('date', '<=', Carbon::parse($request->end_at)->format('Y-m-d'));
       });*/

        /*Tranform data*/
        $result = [];
        foreach ($label as $date){
            $data = [
                'user_id'       => null,
                'start_time'    => null,
                'end_time'      => null,
                'date'          => null,
                'total_time'    => null,
                'note'          => null,
                'created_time'  => null,
                'updated_time'  => null,
                'checkin_time'  => null,
                'checkout_time' => null,
                'day'           => null
            ];
            $schedule =Schedule::query()->where('user_id', $user->id)->where('date', '=', Carbon::parse($date)->format('Y-m-d'))->first();

            if ($schedule){
                $data['user_id']       = $schedule->user_id;
                $data['start_time']    = $schedule->start_time;
                $data['end_time']      = $schedule->end_time;
                $data['date']          = $schedule->date;
                $data['total_time']    = $schedule->total_time;
                $data['note']          = $schedule->note;
                $data['created_time']  = $schedule->created_time;
                $data['updated_time']  = $schedule->updated_time;
                $data['checkin_time']  = $schedule->checkin_time;
                $data['checkout_time'] = $schedule->checkout_time;
                $data['day']           = Carbon::parse($date, 'UTC')->isoFormat('dddd');
            }else{
                $data['user_id']       = $user->id;
                $data['day']           = Carbon::parse($date, 'UTC')->isoFormat('dddd');
                $data['date']          = $date;
            }
            array_push($result, $data);
            //$result[$date] = ($schedule) ? ($schedule) : null;
        }

        return response()->json([
            'schedule_list'       => $result,
            'message'             => 'success'
        ],200);
    }

}
