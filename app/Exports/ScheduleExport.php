<?php

namespace App\Exports;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class ScheduleExport  implements FromQuery, WithHeadings, ShouldAutoSize {
    use Exportable;
    private $from = null;
    private $to = null;
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;

    }
    public function query()
    {
        $schedules = Schedule::query()
            ->join('users', 'schedules.user_id', '=', 'users.id')
            ->select(['users.username','schedules.*' ])
            ->orderBy('date','ASC' )
            ->orderBy('start_time', 'ASC');

        $schedules->when($this->from, function ($q) use (&$request){
            $q->where('date', '>=', Carbon::parse($this->from)->format('Y-m-d'));
        });
        $schedules->when($this->to, function ($q) use (&$request){
            $q->where('date', '<=', Carbon::parse($this->to)->format('Y-m-d'));
        });
        return $schedules->select( [
            'username',
            'user_id',
            'start_time',
            'end_time',
            'total_time',
            'checkin_time',
            'checkout_time',
            'date',
            'note',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            'USERNAME',
            'USER_ID',
            'START_TIME',
            'END_TIME',
            'TOTAL_TIME',
            'CHECKIN_TIME',
            'CHECKOUT_TIME',
            'DATE',
            'NOTE',
        ];
    }
}
