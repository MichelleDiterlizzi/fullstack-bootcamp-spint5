<?php

use Tests\Feature\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;


class EventUpdateTest extends ApiTestCase{


    public function test_event_can_update_with_valid_data(){

        Storage::fake('public');
        $image = UploadedFile::fake()->image('test.jpg');

        $this->createAuthenticatedUser();

        $event = Event::factory()->create();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->putJson('/api/events/' . $event->id, [
                'title' => $event->title,
                'address' => $event->address,
                'event_date' => $event->event_date->format('Y-m-d\TH:i'),
                'price' => $event->price,
                'is_free' => $event->is_free,
                'description' => $event->description,
                'image' => $image,
                'category_id' => $event->category_id
            ]);

        $storedImagePath = $response->json('event.image');
        $this->assertTrue(Storage::disk('public')->exists($storedImagePath));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
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

    public function test_event_cannot_update_with_price_if_is_free(){

        $this->createAuthenticatedUser();

        $event = Event::factory()->create(['is_free' => true]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
            ->putJson('/api/events/' . $event->id, [
                'price' => 12,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price']);
    }

}