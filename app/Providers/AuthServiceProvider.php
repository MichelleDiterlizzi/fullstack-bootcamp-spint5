<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Models\User; // Importa el modelo User
use App\Models\Event; // Importa el modelo Event
use App\Policies\UserPolicy; // Importa la UserPolicy
use App\Policies\EventPolicy; // Importa la EventPolicy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\Policy',
        User::class => UserPolicy::class,
        Event::class => EventPolicy::class,
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