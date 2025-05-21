<?php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

Class CategoryControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_categories_are_seeded_correctly(){
        $this->assertDatabaseHas('categories', ['name' => 'Conciertos y Festivales']);
        $this->assertDatabaseHas('categories', ['name' => 'Eventos Depotivos']);
        $this->assertDatabaseHas('categories', ['name' => 'Arte y Música']);
        $this->assertDatabaseHas('categories', ['name' => 'Cultura']);
        $this->assertDatabaseHas('categories', ['name' => 'Tecnología']);
        $this->assertDatabaseHas('categories', ['name' => 'Talleres y Cursos']);
        $this->assertDatabaseHas('categories', ['name' => 'Eventos Audiovisuales']);
        $this->assertDatabaseHas('categories', ['name' => 'Eventos al Aire Libre']);
        $this->assertDatabaseHas('categories', ['name' => 'Mercadillos']);
        $this->assertDatabaseHas('categories', ['name' => 'Eventos beneficos']);
        $this->assertDatabaseHas('categories', ['name' => 'Ferias']);
        $this->assertDatabaseHas('categories', ['name' => 'Gastronomía']);
        $this->assertDatabaseHas('categories', ['name' => 'Salud y Bienestar']);
        $this->assertDatabaseHas('categories', ['name' => 'Ferias']);
        $this->assertDatabaseHas('categories', ['name' => 'Otros']);
    }


}