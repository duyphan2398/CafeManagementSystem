<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatisticPolicy extends BasePolicy
{
  public function index(User $user){
      return $user->isManager();
  }
  public function dataDiagram1(User $user)
  {
      return $user->isManager();
  }
  public function dataDiagram2(User $user){
      return $user->isManager();
  }
}
