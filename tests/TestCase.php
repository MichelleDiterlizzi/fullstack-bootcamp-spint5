<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;
    
    /**
     * Create a user and get authentication token
     */
    protected function createAuthenticatedUser(array $attributes = []): void
    {
        $this->user = User::factory()->create([
        'email' => 'maria@gmail.com',
        'password' => bcrypt('password123'),
        ]);

        $loginData = [
        'email' => 'maria@gmail.com',
        'password' => 'password123',
        ];
        
        $loginResponse = $this->postJson('/api/login', $loginData);

        
        $this->user->assignRole(Role::where('name', 'user')->where('guard_name', 'api')->first());
        $this->token = $loginResponse->json('access_token');
    }

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');         // Refresca la base de datos (ejecuta las migraciones)
        Artisan::call('db:seed');            // Ejecuta todos los seeders
    }
}