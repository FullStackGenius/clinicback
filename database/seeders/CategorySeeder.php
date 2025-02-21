<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            [
                'name' => 'Accounting & Consulting',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Admin Support',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Customer Service',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Data Science & Analytics',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Design & Creative',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Engineering & Architecture',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IT & Networking',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Legal',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sales & Marketing',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Translation',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Web, Mobile & Software Dev',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Writing',
                'category_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        // Using createMany if it's a collection
        Category::insert($data);
    }
}
