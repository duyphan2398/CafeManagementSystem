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
  /*  private $from = null;
    private $to = null;
    public function __construct($from, $to)
    {
        if ($from || $to) {
            $this->from = Carbon::make($from)->format('Y-m-d');
            $this->to = Carbon::make($to)->format('Y-m-d');
        }

    }*/

    public function query()
    {
       /*if ($this->from || $this->to){
           return Schedule::query()->whereBetween('date', [$this->from, $this->to]);
       }*/
        return Schedule::query();

    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'start_time',
            'end_time',
            'date',
            'total_time',
            'created_at',
            'updated_at'
        ];
    }
}
