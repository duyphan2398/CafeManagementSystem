<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy extends BasePolicy
{
    public function index(User $user){
        return $user->isManager();
    }
    public function show(User $user){
        return $user->isManager();
    }
    public function update(User $user){
        return $user->isAdmin();
    }
    public function destroy(User $user){
        return $user->isAdmin();
    }
    public function updateIngredient(User $user){
        return $user->isAdmin();
    }
    public function deleteIngredient(User $user){
        return $user->isAdmin();
    }
    public function store(User $user){
        return $user->isAdmin();
    }
}
