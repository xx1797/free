<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            ConditionSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
