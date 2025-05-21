<?php

namespace Tests\Feature\api\EventController;

use App\Models\Event;
use App\Models\User;
use Tests\Feature\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventAttendTest extends ApiTestCase
{

    public function test_authenticated_user_can_attend_event_with_guests(){
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

}

    public function test_cannot_attend_event_without_authentication(){
        $event = Event::factory()->create();

        $this->postJson("api/events/{$event->id}/users", ['guests_count' => 1])
            ->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_cannot_attend_event_with_invalid_guest_count(){
        $this->createAuthenticatedUser();
        $event = Event::factory()->create();

        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/events/{$event->id}/users", ['guests_count' => -1])
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_cannot_attend_event_twice(): void
    {
        $this->createAuthenticatedUser();
        $event = Event::factory()->create();

        // First attendance
        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/events/{$event->id}/users", ['guests_count' => 1])
            ->assertStatus(201);

        // Second attempt
        $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson("api/events/{$event->id}/users", ['guests_count' => 1])
            ->assertStatus(409)
            ->assertJson(['message' => 'Ya estás participando en este evento.']);
    }
}