<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        $data = [
            [
                'name' => 'Personal & Professional Coaching',
                'category_id' => 1,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Accounting & Bookkeeping',
                'category_id' => 1,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Financial Planning',
                'category_id' => 1,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'name' => 'Recruiting & Human Resources',
                'category_id' => 1,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Management Consulting & Analysis',
                'category_id' => 1,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Other - Accounting & Consulting',
                'category_id' => 1,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // cat -2
            [
                'name' => 'Data Entry & Transcription Services',
                'category_id' => 2,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Virtual Assistance',
                'category_id' => 2,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Project Management',
                'category_id' => 2,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Market Research & Product Reviews',
                'category_id' => 2,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
              // cat -3
            [
                'name' => 'Community Management & Tagging',
                'category_id' => 3,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Customer Service & Tech Support',
               'category_id' => 3,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // cat -4
            [
                'name' => 'Data Analysis & Testing',
                'category_id' => 4,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Data Extraction/ETL',
               'category_id' => 4,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Data Mining & Management',
                'category_id' => 4,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'AI & Machine Learning',
               'category_id' => 4,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
               // cat -5
               [
                'name' => 'Art & Illustration',
                'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Audio & Music Production',
               'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Branding & Logo Design',
                'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'NFT, AR/VR & Game Art',
               'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Graphic, Editorial & Presentation Design',
                'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Performing Arts',
               'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Photography',
                'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Product Design',
               'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Video & Animation',
               'category_id' => 5,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // cat - 6
            [
                'name' => 'Building & Landscape Architecture',
                'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Chemical Engineering',
               'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Civil & Structural Engineering',
                'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Contract Manufacturing',
               'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Electrical & Electronic Engineering',
                'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Interior & Trade Show Design',
               'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Energy & Mechanical Engineering',
                'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Physical Sciences',
               'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '3D Modeling & CAD',
               'category_id' => 6,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // cat -7
            [
                'name' => 'Database Management & Administration',
                'category_id' => 7,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'ERP/CRM Software',
               'category_id' => 7,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Information Security & Compliance',
                'category_id' => 7,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Network & System Administration',
               'category_id' => 7,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'DevOps & Solution Architecture',
                'category_id' => 7,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // cat - 8
            [
                'name' => 'Corporate & Contract Law',
                'category_id' => 8,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'International & Immigration Law',
               'category_id' => 8,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Finance & Tax Law',
                'category_id' => 8,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Public Law',
               'category_id' => 8,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // cat - 9
            [
                'name' => 'Digital Marketing',
                'category_id' => 9,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Lead Generation & Telemarketing',
               'category_id' => 9,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Marketing, PR & Brand Strategy',
                'category_id' => 9,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // cat - 9
            [
                'name' => 'Language Tutoring & Interpretation',
                'category_id' => 9,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Translation & Localization Services',
               'category_id' => 9,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            // cat - 10
            [
                'name' => 'Blockchain, NFT & Cryptocurrency',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'AI Apps & Integration',
               'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Desktop Application Development',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ecommerce Development',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Game Design & Development',
               'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mobile Development',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Other - Software Development',
               'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Product Management & Scrum',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'QA Testing',
               'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Scripts & Utilities',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Web & Mobile Design',
                'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Web Development',
               'category_id' => 10,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            //cat - 11
            ,[
                'name' => 'Sales & Marketing Copywriting',
               'category_id' => 11,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Content Writing',
                'category_id' => 11,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Editing & Proofreading Services',
                'category_id' => 11,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            ,[
                'name' => 'Professional & Business Writing',
               'category_id' => 11,
                'subcategory_status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        // Using createMany if it's a collection
        SubCategory::insert($data);
    }
}
