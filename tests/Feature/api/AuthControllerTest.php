<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Feature\ApiTestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthControllerTest extends ApiTestCase
{

    public function test_user_can_register_with_valid_credentials()
    {
        $userData = [
        'name' => 'Juan Pérez',
        'email' => 'juan@gmail.com',
        'password' => 'password123',
        'confirm_password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
        'message',
        'user' => ['id', 'name', 'email'],
        'access_token',
        ]);
        $this->assertDatabaseHas('users', ['email' => 'juan@gmail.com']);

    }

    public function test_user_cannot_register_with_invalid_credentials()
    {
        $userData = [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
        'confirm_password' => 'different',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422); 
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'confirm_password']);

    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
        'email' => 'maria@gmail.com',
        'password' => bcrypt('password123'),
        ]);

        $loginData = [
        'email' => 'maria@gmail.com',
        'password' => 'password123',
    ];

    $response = $this->postJson('/api/login', $loginData);

    $response->assertStatus(200)->assertJsonStructure([
        'message',
        'user' => ['id', 'name', 'email'],
        'access_token',
    ]);

    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $loginData = [
        'email' => 'invalid@gmail.com',
        'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);

    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('AuthToken')->accessToken;

        $response = $this->withHeaders([
         'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/users/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logout successful!']);
        $this->assertEmpty($user->tokens);
    }

     public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_returns_valid_token_format(): void
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        
        $user->assignRole(Role::where('name', 'user')->where('guard_name', 'api')->first());

        $response = $this->postJson('/api/login', [
            'email' => 'test@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertArrayHasKey('access_token', $data);
        $this->assertNotEmpty($data['access_token']);
        $this->assertIsString($data['access_token']);
    }
    
}