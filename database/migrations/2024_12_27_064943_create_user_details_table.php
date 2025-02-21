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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('your_experience_id')->nullable();
            $table->foreign('your_experience_id')->references('id')->on('your_experiences');
            $table->unsignedBigInteger('your_goal_id')->nullable();
            $table->foreign('your_goal_id')->references('id')->on('your_goals');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('about_yourself')->nullable();
            $table->text('street_address')->nullable();
            $table->string('state_provience')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_postalcode')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('apt_suite')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('hourly_rate')->nullable();
            $table->integer('services_rate')->nullable();
            $table->integer('income_per_hour')->nullable();
            $table->text('profile_headline')->nullable();
            $table->integer('next_step')->default(0);
            $table->integer('completed_steps')->default(0);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
