<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;




class EventStoreTest extends TestCase
{

    use RefreshDatabase;

    public function test_event_can_be_stored()
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test.jpg');

        $this->createAuthenticatedUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/events');

        $event = Event::factory()->make();

        $response = $this->postJson('/api/events', [
            'title' => $event->title,
            'address' => $event->address,
            'event_date' => $event->event_date->format('Y-m-d\TH:i'),
            'price' => $event->price,
            'is_free' => $event->is_free,
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
}