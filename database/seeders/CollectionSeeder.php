<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 151; $i++) {
            DB::table('cards')->insert([
                'name' => Str::random(10),
                'number' => rand(1, 151),
                'type' => Str::random(10),
                'collected' => rand(0, 1),
            ]);
        }
    }
}
