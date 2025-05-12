<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('oauth_clients')->insert([
            [
                'id' => '9eca6133-3fa0-4408-ba08-e3007d687659',
                'user_id' => null,
                'name' => 'Personal Access Client',
                'secret' => Hash::make('hfzQyfVUvtq9dWfSd63zKWKxfhOC6r0JG3zLHSlw'),
                'redirect' => 'http://localhost/',
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => '9eca6133-4a08-48cb-9762-337dabc7714c',
                'user_id' => null,
                'name' => 'Password Grant Client',
                'secret' => Hash::make('QdMnRU2MgOC2ZFiUvP7qUKZl3lbXULWJwpjCDHwD'),
                'redirect' => 'http://localhost/',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
