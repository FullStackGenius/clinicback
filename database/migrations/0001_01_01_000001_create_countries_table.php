<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
        DB::table('countries')->insert([
            ['name' => 'India', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'United States', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Canada', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Australia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Germany', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'France', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'United Kingdom', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Italy', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Spain', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Brazil', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'China', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Russia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Japan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mexico', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'South Africa', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Egypt', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Argentina', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nigeria', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Indonesia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Saudi Arabia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turkey', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pakistan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bangladesh', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vietnam', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thailand', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'South Korea', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Malaysia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Colombia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ukraine', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Poland', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Iraq', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Afghanistan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kenya', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Peru', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Algeria', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sudan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Morocco', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uzbekistan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Malaysia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Iraq', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Venezuela', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nepal', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sri Lanka', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lithuania', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ecuador', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Romania', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chile', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Czech Republic', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Honduras', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bulgaria', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuba', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Greece', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Portugal', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'New Zealand', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Angola', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Belarus', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finland', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Slovakia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Norway', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ireland', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Denmark', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Estonia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Latvia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Switzerland', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Croatia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Georgia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bosnia and Herzegovina', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Macedonia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Armenia', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kyrgyzstan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tajikistan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Turkmenistan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Qatar', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jordan', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oman', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bahrain', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'United Arab Emirates', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lebanon', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cyprus', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Malta', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
