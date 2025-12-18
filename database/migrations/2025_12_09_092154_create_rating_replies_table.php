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
        Schema::create('rating_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rating_id');
            $table->foreign('rating_id')->references('id')->on('ratings');
            $table->unsignedBigInteger('reply_by');
            $table->foreign('reply_by')->references('id')->on('users');
            $table->unsignedBigInteger('reply_to');
            $table->foreign('reply_to')->references('id')->on('users');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_replies');
    }
};
