<?php

namespace App\Policies;

use App\Models\User;
use App\Promotion;
use Illuminate\Auth\Access\HandlesAuthorization;

class PromotionPolicy extends BasePolicy
{
    public function index(User $user)
    {
        return $user->isManager();
    }

    public function destroy(User $user){
        return $user->isAdmin();
    }

    public function create(User $user){
        return $user->isAdmin();
    }

    public function show(User $user){
        return $user->isAdmin();
    }

    public function update(User $user){
        return $user->isAdmin();
    }

}
