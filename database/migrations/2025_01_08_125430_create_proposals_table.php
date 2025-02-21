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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id'); // Foreign Key referencing jobs
            $table->unsignedBigInteger('freelancer_id'); // Foreign Key referencing freelancers (users table)
            $table->decimal('bid_amount', 10, 2); // Freelancer's bid amount
            $table->text('cover_letter'); // Freelance cover letter
            $table->enum('status', ['pending', 'shortlisted', 'interview', 'hired', 'rejected'])->default('pending');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
