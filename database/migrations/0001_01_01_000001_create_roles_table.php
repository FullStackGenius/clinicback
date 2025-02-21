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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Role name, must be unique
            $table->text('description')->nullable(); // Optional description of the role
            $table->timestamps(); // Created_at and Updated_at
            $table->softDeletes(); // Optional: For soft deleting roles
        });
        DB::table('roles')->insert([
            ['name' => 'admin','description' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('roles')->insert([
            ['name' => 'client', 'description' => 'Client seeking to hire','created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('roles')->insert([
            ['name' => 'freelancer','description' => 'Accountant seeking work' , 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
