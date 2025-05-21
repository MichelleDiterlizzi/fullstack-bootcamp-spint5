<?php

namespace Tests\Feature\api\EventController;

use Tests\Feature\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventFilterControllerTest extends ApiTestCase
{

     public function test_can_get_popular_events_ordered_by_attendees()
{
    $response = $this->getJson('/api/events/popular?limit=5');
    

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message',
        'data' => [
            '*' => [
                'id',
                'title',
                'event_date',
                'is_free',
                'attendees_count',
            ],
        ],
    ]);
}

    public function test_can_get_free_events()
{
    $response = $this->getJson('/api/events/free?limit=5');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message',
        'data' => [
            '*' => [
                'id',
                'title',
                'event_date',
                'is_free',
            ],
        ],
    ]);

}

    public function test_can_get_events_before_time()
{
    $response = $this->getJson('/api/events/before-time?limit=5');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message',
        'data' => [
            '*' => [
                'id',
                'title',
                'event_date',
                'is_free',
            ],
        ],
    ]);
}

    public function test_can_get_events_after_time()
{
    $response = $this->getJson('/api/events/after-time?limit=5');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'message',
        'data' => [
            '*' => [
                'id',
                'title',
                'event_date',
                'is_free',
            ],
        ],
    ]);
}

}