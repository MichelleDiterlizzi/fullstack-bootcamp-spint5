<?php

use Tests\Feature\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;

class EventShowTest extends ApiTestCase{

    public function test_event_can_be_shown()
    {
        $this->createAuthenticatedUser();

        $event = Event::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/events/' . $event->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'event' => [
                'id',
                'title',
                'address',
                'event_date',
                'price',
                'is_free',
                'description',
                'image',
                'category_id',
                'creator_id',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function test_event_not_found()
    {
        $this->createAuthenticatedUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/events/999999');

        $response->assertStatus(404);

        $response->assertJsonStructure(['message']);
    }


}