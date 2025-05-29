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
        Schema::create('contract_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id'); // Foreign Key referencing jobs
            $table->unsignedBigInteger('project_id'); // Foreign Key referencing jobs
            $table->unsignedBigInteger('client_id'); // Foreign Key referencing freelancers (users table)
            $table->string('payment_intent_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10);
            $table->string('status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('transfer_group')->nullable();
            $table->json('stripe_response')->nullable();
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_payments');
    }
};
