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
        Schema::create('all_payment_transactions', function (Blueprint $table) {
            // $table->id();
            // $table->timestamps();

            $table->id();
            $table->unsignedBigInteger('payer_id')->nullable();   // foreign key to users table
            $table->unsignedBigInteger('receiver_id')->nullable(); // foreign key to users table
            $table->string('payment_for')->nullable()->comment('contract , milestone');
            $table->morphs('payable'); // payable_id, payable_type
            $table->timestamps();
            // Foreign keys
            $table->foreign('payer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_payment_transactions');
    }
};
