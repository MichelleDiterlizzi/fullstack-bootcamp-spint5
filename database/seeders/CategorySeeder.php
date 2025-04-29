<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Conciertos y Festivales', 'description' => 'Eventos musicales diversos.', 'image' => 'img/categories/concierto1.jpg'],
            ['name' => 'Eventos Depotivos', 'description' => 'Eventos deportivos para todas las edades.', 'image' => 'img/categories/sport1.jpg'],
            ['name' => 'Arte y Música', 'description' => 'Exposiciones y eventos artísticos.', 'image' => 'img/categories/teatre5.jpg'],
            ['name' => 'Cultura', 'description' => 'Eventos culturales, exposiciones, museos literatura y más.', 'image' => 'img/categories/museum1.jpg'],
            ['name' => 'Tecnología', 'description' => 'Conferencias y eventos tecnológicos.', 'image' => 'img/categories/technology5.jpg'],
            ['name' => 'Talleres y Cursos', 'description' => 'Cursos y eventos educativos.', 'image' => 'img/categories/workshop1.jpg'],
            ['name' => 'Eventos Audiovisuales', 'description' => 'Shows de comedia y entretenimiento.', 'image' => 'img/categories/technology2.jpg'],
            ['name' => 'Eventos al Aire Libre', 'description' => 'Eventos académicos y profesionales.', 'image' => 'img/categories/outside1.jpg'],
            ['name' => 'Mercadillos', 'description' => 'Eventos de compra y venta.', 'image' => 'img/categories/mercadillo3.jpg'],
            ['name' => 'Eventos beneficos', 'description' => 'Eventos con fines benéficos.', 'image' => 'img/categories/charity2.jpg'],
            ['name' => 'Ferias', 'description' => 'Ferias comerciales y exposiciones.','image' => 'img/categories/feria3.jpg'],
            ['name' => 'Gastronomía', 'description' => 'Eventos relacionados con la comida y la bebida.', 'image' => 'img/categories/food2.jpg'],
            ['name' => 'Salud y Bienestar', 'description' => 'Eventos relacionados con la salud y el bienestar.', 'image' => 'img/categories/sport1.jpg'],
            ['name' => 'Familia y Niños', 'description' => 'Eventos familiares y para niños.', 'image' => 'img/categories/family2.jpg'],
            ['name' => 'Otros', 'description' => 'Eventos diversos que no encajan en otras categorías.', 'image' => 'img/categories/sport2.jpg'],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(['name' => $categoryData['name']], $categoryData);
        }
    }
}
