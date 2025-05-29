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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('project_status')->comment('1 - pending , 2 - draft , 3 - publish , 4 - closed , 5 - assigned')->default(1);
            $table->integer('budget_type')->comment('1 - hourly , 2 - fixed')->nullable();
            $table->integer('hourly_from')->nullable();
            $table->integer('hourly_to')->nullable();
            $table->integer('fixed_rate')->nullable();
            $table->unsignedBigInteger('project_type_id')->nullable();
            $table->foreign('project_type_id')->references('id')->on('project_types');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('project_scope_id')->nullable();
            $table->foreign('project_scope_id')->references('id')->on('project_scopes');
            $table->unsignedBigInteger('project_duration_id')->nullable();
            $table->foreign('project_duration_id')->references('id')->on('project_durations');
            $table->unsignedBigInteger('project_experience_id')->nullable();
            $table->foreign('project_experience_id')->references('id')->on('project_experiences');
            $table->integer('next_step')->default(1);
            $table->integer('completed_steps')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
