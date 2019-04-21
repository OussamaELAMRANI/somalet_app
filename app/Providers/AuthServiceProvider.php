<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Add routes for Passport
        Passport::routes();
        // Scopes of each
        Passport::tokensCan([
            'controller' => 'Has a Controller Right',
            'magaziner' => 'Making and preparing our Products',
            'comptable' => 'comptable of the container',
            'admin' => 'Super User, Or the Boss',
            // add more
            'guest' => 'simple user waiting the role',
        ]);

//        Token Lifetimes
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));

        Passport::personalAccessClientId(1);

    }
}
