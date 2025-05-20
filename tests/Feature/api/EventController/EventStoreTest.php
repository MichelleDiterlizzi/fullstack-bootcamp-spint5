<?php

use Tests\Feature\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class EventStoreTest extends ApiTestCase
{

    public function test_event_can_be_stored()
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test.jpg');

        $this->createAuthenticatedUser();

        $event = Event::factory()->make();

        $response = $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/events', [
            'title' => $event->title,
            'address' => $event->address,
            'event_date' => $event->event_date->format('Y-m-d\TH:i'),
            'price' => $event->price,
            'is_free' => false,
            'description' => $event->description,
            'image' => $image,
            'category_id' => $event->category_id]);

        $response->assertStatus(201);

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

        $storedImagePath = $response->json('event.image');
        $this->assertTrue(Storage::disk('public')->exists($storedImagePath));

        $this->assertDatabaseHas('events', [
            'title' => $event->title,
            'address' => $event->address,
        ]);

    }

    public function test_event_cannot_be_stored_with_invalid_data()
    {
        $this->createAuthenticatedUser();

        $response = $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/events', [
            'title' => '',
            'address' => '',
            'event_date' => '',
            'price' => 'aaaa',
            'is_free' => '',
            'description' => '',
            'image' => UploadedFile::fake()->create('archivo.pdf', 100),
            'category_id' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
        $response->assertJsonValidationErrors(['title', 'address', 'event_date', 'price', 'is_free', 'description', 'image', 'category_id']);

    }

    public function test_event_cannot_be_stored_without_authentication()
    {
        $response = $this->postJson('/api/events', [
            'title' => 'Test Event',
            'address' => '123 Test St',
            'event_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
            'price' => 100,
            'is_free' => false,
            'description' => 'This is a test event.',
            'image' => UploadedFile::fake()->image('test.jpg'),
            'category_id' => 1
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_price_is_null_when_event_is_free()
    {
        $this->createAuthenticatedUser();

        $event = Event::factory()->make(['is_free' => true]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/events', [
            'title' => $event->title,
            'address' => $event->address,
            'event_date' => $event->event_date->format('Y-m-d\TH:i'),
            'price' => null,
            'is_free' => true,
            'description' => $event->description,
            'image' => UploadedFile::fake()->image('test.jpg'),
            'category_id' => $event->category_id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('events', [
            'title' => $event->title,
            'price' => null,
        ]);
    }

    public function test_price_is_required_when_event_is_not_free()
    {
        $this->createAuthenticatedUser();

        $event = Event::factory()->make(['is_free' => false]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/events', [
            'title' => $event->title,
            'address' => $event->address,
            'event_date' => $event->event_date->format('Y-m-d\TH:i'),
            'price' => null,
            'is_free' => false,
            'description' => $event->description,
            'image' => UploadedFile::fake()->image('test.jpg'),
            'category_id' => $event->category_id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price']);
    }
}