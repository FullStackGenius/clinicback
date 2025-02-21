<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageProficiencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proficiencies = [
            ['name' => 'Basic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Conversational', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fluent', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Native or Bilingual', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('language_proficiencies')->insert($proficiencies);
    }
}
