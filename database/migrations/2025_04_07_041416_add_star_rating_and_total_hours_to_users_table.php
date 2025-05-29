<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('star_rating', 2, 1)->nullable()->after('email'); // e.g., 4.7
            $table->unsignedInteger('total_hours')->nullable()->after('star_rating'); // e.g., 4
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['star_rating', 'total_hours']);
        });
    }
};
