<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy extends BasePolicy
{
    public function schedule(User $user){
        return $user->isManager();
    }

    public function createSchedule(User $user){
        return $user->isManager();
    }

    public function getAllUsersWithoutTrashed(User $user){
        return $user->isManager();
    }

    public function getScheduleToday(User $user){
        return $user->isManager();
    }

    public function deleteSchedule(User $user){
        return $user->isManager();
    }

    public function getListScheduleFillter(User $user){
        return $user->isManager();
    }

    public function exportScheduleCsv(User $user){
        return $user->isManager();
    }

    public function checkin(User $user){
        return $user->isManager();
    }

    public function checkout(User $user){
        return $user->isManager();
    }
}
