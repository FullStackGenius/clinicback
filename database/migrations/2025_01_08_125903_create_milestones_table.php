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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id'); // Foreign Key referencing contracts
            $table->string('title'); // Milestone title (e.g., "Initial Draft", "Final Version")
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2); // The payment amount for this milestone
            $table->decimal('completion_percentage', 5, 2)->default(0); // Percentage of work completed
            $table->enum('status', ['pending', 'completed', 'paid'])->default('pending'); // Status of milestone
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
