<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PassportSeeder::class);

        $user1 = User::factory()->create([
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
        ]);

        Event::factory()->create([
            'title' => 'Concierto Épico',
            'address' => 'Estadio Olímpico, Barcelona',
            'event_date' => Carbon::now()->addDays(7),
            'price' => 50.00,
            'is_free' => false,
            'description' => 'Un concierto inolvidable con tu banda favorita.',
            'image' => 'img/concierto3.jpg',
            'creator_id' => $user1->id,
            'category_id' => 1,
        ]);

        Event::factory()->create([
            'title' => 'Festival de Cine Indie',
            'address' => 'Cine Ciutat, L\'Hospitalet',
            'event_date' => Carbon::now()->addWeeks(2),
            'price' => 10.00,
            'is_free' => false,
            'description' => 'Proyecciones de las mejores películas independientes.',
            'image' => 'img/teatre1.jpg',
            'creator_id' => $user2->id,
            'category_id' => 7,
        ]);

        Event::factory()->create([
            'title' => 'Taller de Programación para Principiantes',
            'address' => 'Tech Space, Barcelona',
            'event_date' => Carbon::now()->addMonth(),
            'is_free' => true,
            'description' => 'Aprende a programar desde cero en este taller gratuito.',
            'image' => 'img/technology4.jpg', 
            'creator_id' => $user1->id,
            'category_id' => 6,
        ]);

        Event::factory()->create([
            'title' => 'Exposición de Arte Moderno',
            'address' => 'Museo MACBA, Barcelona',
            'event_date' => Carbon::now()->addDays(10),
            'price' => 15.00,
            'is_free' => false,
            'description' => 'Descubre las últimas tendencias del arte contemporáneo.',
            'image' => 'img/museum3.jpg',
            'creator_id' => $user2->id,
            'category_id' => 4,
        ]);
    }
}
