<?php

namespace App\Policies;

use App\Material;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPolicy extends BasePolicy
{
   public function index(User $user)
   {
       return  $user->isManager();
   }
   public function show(User $user){
       return $user->isManager();
   }
    public function create(User $user){
        return $user->isManager();
    }
    public function getMaterial(User $user){
        return $user->isManager();
    }
    public function update(User $user){
        return $user->isManager();
    }
    public function delete(User $user){
       return $user->isManager();
    }
    public function search(User $user){
       return $user->isManager();
    }

}
