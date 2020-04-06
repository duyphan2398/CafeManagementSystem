<?php

namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;

trait AddUser{

    public function user(){
        return $this->belongsto(User::class, 'user_id');
    }

    public function setUserIdAttribute($user_id){
        $this->attributes['user_id'] = $user_id;
    }
}
