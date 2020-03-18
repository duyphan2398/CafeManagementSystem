<?php

namespace App\Models;

use App\Traits\AddUser;
use App\Traits\ParseTimeStamp;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class Schedule extends Model
{
    use Exportable;
    use AddUser;
    use ParseTimeStamp;




    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'date',
        'total_time',
        'created_time',
        'updated_time'
    ];

  /*  public function setStartTimeAttribute($value){
        return  $this->attributes['start_time'] = Carbon::make($value)->format('H:i');
    }

    public function setEndTimeAttribute($value){
        return  $this->attributes['end_time'] = Carbon::make($value)->format('H:i');
    }*/

   /* public function setTotalTimeAttribute($value){
        return  $this->attributes['total_time'] = Carbon::make($value)->format('H:i');
    }*/

    public function setDateAttribute($value){
        return  $this->attributes['date'] = Carbon::make($value)->format('Y-m-d');
    }
    public function getDateAttribute($value){
        return Carbon::parse($value)->format('d-m-Y');
    }


}
