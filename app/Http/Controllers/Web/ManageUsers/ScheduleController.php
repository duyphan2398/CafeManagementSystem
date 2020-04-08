<?php


namespace App\Http\Controllers\Web\ManageUsers;

use App\Exports\ScheduleExport;
use App\Http\Controllers\WebBaseController;
use App\Http\Requests\NewScheduleRequest;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends WebBaseController
{
    /*---------------schedule-----------------*/
    public function schedule(){
        return view('workspace.manageUsers.schedule');
    }

    public function createSchedule(NewScheduleRequest $request){
        if ( $request->validated()) {
            $user = User::where('username', '=', $request->username)->first();
            $schedule = new Schedule();
            $schedule->fill($request->all());
            $schedule->start_time = Carbon::make($request->start_time)->format('H:i');
            $schedule->end_time = Carbon::make($request->end_time)->format('H:i');
            $schedule->setUserIdAttribute($user->id);
            if ( $user && $schedule->save()){
                return response()->json([
                    'status' => 'success',
                    'schedule' => $schedule
                ],201);
            }
            return response()->json([
                'status' => 'fails',
            ],500);

        }
        else{
            return response()->json([
                "status" =>"fails",
                "validated" =>$request->validated()
            ],422);
        }
    }

    public function getAllUsersWithoutTrashed(){
        $users = User::all();
        return response()->json([
            'users' => $users
        ],200);
    }

    public function getScheduleToday(){
        $schedules = Schedule::join('users', 'schedules.user_id', '=', 'users.id')
            ->select(['users.username','schedules.*' ])
            ->whereDate('date', '=', Carbon::today()->toDateString())
            ->orderBy('start_time')
            ->get();

        if ($schedules->count()) {
            return response()->json([
                'status' =>'success',
                'schedules'=> $schedules
            ],200);
        }
        return response()->json([
            'status' => 'Could Not Found'
        ],404);
    }

    public function deleteSchedule(Request $request){
        $schedule = Schedule::find($request->schedule_id);
        $date = $schedule->date;
        if ($schedule->delete()){
            return response()->json([
                'status' => 'success',
                'date' => $date
            ],200);
        }

    }

    public function  getListScheduleFillter(Request $request){
        $schedules = Schedule::query()
            ->join('users', 'schedules.user_id', '=', 'users.id')
            ->select(['users.username','schedules.*' ])
            ->orderBy('date','ASC' )
            ->orderBy('start_time', 'ASC');

        $schedules->when($request->from, function ($q) use (&$request){
            $q->where('date', '>=', Carbon::parse($request->from)->format('Y-m-d'));
        });
        $schedules->when($request->to, function ($q) use (&$request){
            $q->where('date', '<=', Carbon::parse($request->to)->format('Y-m-d'));
        });

        if ((count($schedules->get()))){

            return response()->json([
                'status'=> ' success',
                'schedules' => $schedules->get()
            ],200);
        }
        return response()->json([
            'status'=> 'Not found',
        ],404);

    }

    public function exportScheduleCsv(Request $request){
        $from = $request->fromFillter;
        $to = $request->toFillter;
        return (new ScheduleExport($from, $to))->download('EmployeeSchedule('.$from.'-To-'.$to.').csv', \Maatwebsite\Excel\Excel::CSV,  ['Content-Type' => 'text/csv']);
    }
}
