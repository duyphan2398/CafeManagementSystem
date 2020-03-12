<?php

namespace App\Traits;

use Carbon\Carbon;

trait ParseCreatedAt{

    public function getCreatedAtAttribute($created_at){
        return Carbon::parse($created_at)->format('H:i d-m-Y');
    }
}
