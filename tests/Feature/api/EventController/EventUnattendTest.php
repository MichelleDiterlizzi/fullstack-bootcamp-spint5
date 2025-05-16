<?php

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventUnattendTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_unattend_event_with_guests(){
        $this->createAuthenticatedUser();
        $event = Event::factory()->create();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/events/{$event->id}/users", ['guests_count' => 2])
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'event' => ['id'], 'user' => ['id'], 'guests_count']);

        $this->assertDatabaseHas('event_users', [
            'user_id' => $this->user->id,
            'event_id' => $event->id,
            'guests_count' => 2,
        ]);

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/events/{$event->id}/users")
            ->assertStatus(200)
            ->assertJson(['message' => 'Desasistencia registrada con éxito.']);

        $this->assertDatabaseMissing('event_users', [
            'user_id' => $this->user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_cannot_unattend_event_without_authentication(){
        $event = Event::factory()->create();

        $this->deleteJson("api/events/{$event->id}/users")
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_cannot_unattend_event_if_not_attending(){
        $this->createAuthenticatedUser();
        $event = Event::factory()->create();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("api/events/{$event->id}/users")
            ->assertStatus(409)
            ->assertJson(['message' => 'No estás participando en este evento.']);
    }

}