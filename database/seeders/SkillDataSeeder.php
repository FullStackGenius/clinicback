<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Certified Public Accountant (cPA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Chartered Financial Analyst (CFA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Certified Management Accountant (CMA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Certified Internal Auditor (CIA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Certified Fraud Examiner (CFE)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Certified Information Systems Auditor (CISA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Enrolled Agent (EA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Certified Valuation Analyst (CVA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Certified Government Financial Manager (CGFM)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Accredited Business Advisor/Accountant (ABA)',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        // Using createMany if it's a collection
        Skill::insert($data);
    }
}
