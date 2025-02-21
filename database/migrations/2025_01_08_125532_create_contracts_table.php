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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unsignedBigInteger('proposal_id')->nullable();
            $table->foreign('proposal_id')->references('id')->on('proposals');
            $table->unsignedBigInteger('freelancer_id')->nullable();
            $table->foreign('freelancer_id')->references('id')->on('users');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->enum('type', ['hourly', 'fixed'])->default('hourly'); // Contract type (hourly or fixed-price)
            $table->decimal('amount', 10, 2); // Amount of the contract (can be hourly or fixed)
            $table->enum('status', ['active', 'completed', 'terminated'])->default('active'); // Contract status
            $table->enum('payment_type', ['milestone', 'lump_sum'])->default('milestone'); // Payment type for contract
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
