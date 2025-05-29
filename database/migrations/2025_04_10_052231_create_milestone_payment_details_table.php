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
        Schema::create('milestone_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('milestone_id');
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->string('transfer_id');
            $table->string('destination_account');
            $table->string('destination_payment')->nullable();
            $table->string('balance_transaction_id');
            $table->string('currency');
            // $table->bigInteger('amount');
            $table->decimal('amount', 10, 2); 
            $table->decimal('actual_milestone_amount', 10, 2); 
            $table->decimal('platform_fee_charges', 10, 2); 
            $table->string('transfer_group');
            $table->boolean('reversed')->default(false);
            $table->timestamp('transferred_at')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone_payment_details');
    }
};
