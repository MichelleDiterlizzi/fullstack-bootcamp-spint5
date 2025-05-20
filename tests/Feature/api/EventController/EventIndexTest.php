<?php

namespace Tests\Feature;

use Tests\Feature\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\User;

class EventIndexTest extends ApiTestCase
{
    public function it_returns_all_events()
    {

        $user = User::factory()->create();
        $token = $user->createToken('AuthToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/events'); 

        Event::factory()->count(3)->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
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
                    'updated_at',
                ]
            ]
        ]);
    }
}