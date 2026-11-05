<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\SekolahSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SekolahSeeder::class,
            UserSeeder::class,
        ]);
    }
}
