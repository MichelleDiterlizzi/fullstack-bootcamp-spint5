<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Database\Seeders\PassportSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


abstract class ApiTestCase extends TestCase
{
    use DatabaseMigrations; // Mantenemos este trait

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        if (env('DB_CONNECTION') === 'sqlite' && env('DB_DATABASE') === ':memory:') {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed', ['--class' => RoleSeeder::class]);
            Artisan::call('db:seed', ['--class' => PassportSeeder::class]);
            Artisan::call('db:seed', ['--class' => CategorySeeder::class]);
        } else {
            // Si no es sqlite en memoria, usa el comportamiento por defecto de DatabaseMigrations
            $this->seed(RoleSeeder::class);
            $this->seed(PassportSeeder::class);
        }
    }

    /**
     * Crea un usuario autenticado y guarda su token.
     */
    protected function createAuthenticatedUser(array $attributes = []): void
    {
        $defaultAttributes = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ];

        $this->user = User::factory()->create(array_merge($defaultAttributes, $attributes));

        $userRole = Role::where('name', 'user')->where('guard_name', 'api')->first();
        // Asigna rol si existe
        if (method_exists($this->user, 'assignRole')) {
            $this->user->assignRole($userRole);
        }

        // Usa el endpoint de login para obtener el token
        $loginResponse = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password123',
        ]);

        $this->token = $loginResponse->json('access_token');
    }
}