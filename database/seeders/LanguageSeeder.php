<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'Afrikaans', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Albanian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Amharic', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arabic', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Armenian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Azerbaijani', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Basque', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bengali', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bosnian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bulgarian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Catalan', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chinese (Simplified)', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chinese (Traditional)', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Croatian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Czech', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Danish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dutch', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'English', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Esperanto', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Estonian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Filipino', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finnish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'French', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Galician', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Georgian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'German', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Greek', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gujarati', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Haitian Creole', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hebrew', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hindi', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hungarian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Icelandic', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Indonesian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Irish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Italian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Japanese', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Javanese', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kannada', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kazakh', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Khmer', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Korean', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kurdish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kyrgyz', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lao', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Latvian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lithuanian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Macedonian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Malay', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Malayalam', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maltese', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marathi', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mongolian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nepali', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Norwegian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pashto', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Persian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Polish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Portuguese', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Punjabi', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Romanian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Russian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Serbian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sinhala', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Slovak', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Slovenian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spanish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swahili', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swedish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tamil', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Telugu', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thai', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turkish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ukrainian', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Urdu', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uzbek', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vietnamese', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Welsh', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Xhosa', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Yiddish', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zulu', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('languages')->insert($languages);
    }
}
