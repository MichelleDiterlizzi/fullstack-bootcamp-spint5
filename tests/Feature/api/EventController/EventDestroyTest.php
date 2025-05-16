<?php

namespace Tests\Feature\api\EventController;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDestroyTest extends TestCase
{
    use RefreshDatabase;

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
}