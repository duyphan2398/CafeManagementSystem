<?php


namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class BasePolicy
{
    use HandlesAuthorization;

    public function before(User $user){
        if ($user->isAdmin()) {
            return true;
        }
    }
}
