<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:fresh');         // Refresca la base de datos (ejecuta las migraciones)
        Artisan::call('db:seed');            // Ejecuta todos los seeders
        //Artisan::call('db:seed', ['--class' => 'PassportSeeder']); // Ejecuta un seeder específico
    }
}