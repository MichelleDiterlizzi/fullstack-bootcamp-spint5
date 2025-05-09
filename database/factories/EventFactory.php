<?php

   namespace Database\Factories;

   use App\Models\Category;
   use App\Models\Event;
   use App\Models\User;
   use Illuminate\Database\Eloquent\Factories\Factory;
   use Illuminate\Support\Carbon;

   class EventFactory extends Factory
   {
       protected $model = Event::class;

       public function definition(): array
       {
           return [
               'title' => $this->faker->sentence(),
               'address' => $this->faker->address(),
               'event_date' => Carbon::now()->addDays(rand(1, 30)),
               'price' => $this->faker->randomFloat(2, 0, 100),
               'is_free' => $this->faker->boolean(20),
               'description' => $this->faker->paragraph(3),
               // 'image' => 'img/' . $this->faker->image(storage_path('app/public/img'), 400, 300, null, false),
                'image' => 'img/default.jpg',
               'creator_id' => User::factory(),
               'category_id' => Category::inRandomOrder()->first()->id,
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),
           ];
       }
   }
