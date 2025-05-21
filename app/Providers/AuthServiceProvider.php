<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\Policy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    
    public function boot()
{
    $this->registerPolicies();

    // Habilita el flujo Password Grant de Passport
    Passport::enablePasswordGrant();

    // Permite acceso total al super-admin
    Gate::before(function ($user, $ability) {
        return ($user->hasRole('super-admin')) ? true : null;
    });
}
}