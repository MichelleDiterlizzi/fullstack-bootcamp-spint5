<?php

use App\Models\Event;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_profile()
    {
        $this->createAuthenticatedUser();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('api/users/profile')
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'data' => ['id', 'name', 'email']]);
    }

    public function test_unauthenticated_user_cannot_get_profile()
    {
        $this->getJson('api/users/profile')
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_authenticated_user_can_update_profile()
    {
        $this->createAuthenticatedUser();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('api/users/profile', [
                'name' => 'qqq',
                'email' => 'test00@gmail.com',
                'password' => '',
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'user' => ['id', 'name', 'email']])
            ->assertJson(['message' => 'usuario actualizado con éxito!']);
    }

    public function test_user_cannot_update_with_already_used_email()
    {
        $this->createAuthenticatedUser();

        $otherUser = User::factory()->create();
        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('api/users/profile', [
                'name' => 'Updated Name',
                'email' => $otherUser->email,
                'password' => '',
            ])
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJson(['message' => 'Validation Error']);
    }



    public function test_unauthenticated_user_cannot_update_profile()
    {
        $this->putJson('api/users/profile', [
            'name' => 'Updated Name',
            'email' => '',
            'password' => 'newpassword',
        ])
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_authenticated_user_cannot_update_profile_with_invalid_data()
    {
        $this->createAuthenticatedUser();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('api/users/profile', [
                'name' => '',
                'email' => 'invalid-email',
                'password' => 'short',
            ])
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors'])
            ->assertJson(['message' => 'Validation Error']);
    }

    public function test_authenticated_user_can_delete_profile()
    {
        $this->createAuthenticatedUser();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('api/users/profile')
            ->assertStatus(200)
            ->assertJson(['message' => 'Usuario eliminado con éxito!']);
    }

    public function test_unauthenticated_user_cannot_delete_profile()
    {
        $this->deleteJson('api/users/profile')
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_user_cannot_delete_other_user_profile()
    {
        $this->createAuthenticatedUser();

        $otherUser = User::factory()->create();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/users/{$otherUser->id}")
            ->assertStatus(403)
            ->assertJson(['message' => 'Forbidden']);
    }

    public function test_admin_can_delete_other_user_profile()
    {
        $this->createAuthenticatedUser();

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'api')->first();
        $this->user->assignRole($adminRole);

        $otherUser = User::factory()->create();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/users/{$otherUser->id}")
            ->assertStatus(200)
            ->assertJson(['message' => 'Usuario eliminado con éxito!']);
    }
}