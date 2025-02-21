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
        Schema::create('mileston_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('milestone_id'); // Foreign Key referencing milestones
            $table->decimal('amount', 10, 2); // The payment amount made for the milestone
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending'); // Payment status
            $table->timestamp('paid_at')->nullable(); // Timestamp of when the milestone was paid
            $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mileston_payments');
    }
};
