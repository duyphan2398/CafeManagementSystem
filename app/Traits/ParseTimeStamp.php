<?php

namespace App\Traits;

use Carbon\Carbon;

trait ParseTimeStamp{

    public function getCreatedAtAttribute($created_at){
        return Carbon::parse($created_at)->format('H:i d-m-Y');
    }

    public function getUpdatedAtAttribute($updated_at){
        return Carbon::parse($updated_at)->format('H:i d-m-Y');
    }
}
