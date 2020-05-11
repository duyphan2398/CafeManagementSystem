<?php

namespace App\Providers;

use App\Models\Material;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Schedule;
use App\Models\User;
use App\Policies\MaterialPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PromotionPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class                 =>  UserPolicy::class,
        Schedule::class             =>  SchedulePolicy::class,
        Product::class              =>  ProductPolicy::class,
        Promotion::class            =>  PromotionPolicy::class,
        Material::class             =>  MaterialPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
