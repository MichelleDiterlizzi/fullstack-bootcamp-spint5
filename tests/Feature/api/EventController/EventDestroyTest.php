<?php

namespace Tests\Feature\api\EventController;

use App\Models\Event;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\ApiTestCase;

class EventDestroyTest extends ApiTestCase
{

    public function test_user_can_delete_event(){
        $this->createAuthenticatedUser();

        $event = Event::factory()->create(['creator_id' => $this->user->id]);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/events/{$event->id}")
            ->assertStatus(200)
            ->assertJson(['message' => 'Evento eliminado con éxito!']);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_cannot_delete_non_existent_event(){
        
        $this->createAuthenticatedUser();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/events/99999")
            ->assertStatus(404);
    }

    public function test_user_cannot_delete_event_not_created_by_them(){
        $this->createAuthenticatedUser();
        $otherUser = User::factory()->create();

        $event = Event::factory()->create(['creator_id' => $otherUser->id]);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/events/{$event->id}")
            ->assertStatus(403)
            ->assertJson(['message' => 'Forbidden']);
    }
    
    public function test_admin_can_delete_any_event(){
        $this->createAuthenticatedUser();

        $event = Event::factory()->create(['creator_id' => 1]);

        $adminRole = Role::where('name', 'admin')->where('guard_name', 'api')->first();
        $this->user->assignRole($adminRole);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/events/{$event->id}")
            ->assertStatus(200)
            ->assertJson(['message' => 'Evento eliminado con éxito!']);

    }
}