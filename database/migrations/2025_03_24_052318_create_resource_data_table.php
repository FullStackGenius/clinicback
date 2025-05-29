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
        Schema::create('resource_data', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->unsignedBigInteger('resource_category_id');
            $table->foreign('resource_category_id')->references('id')->on('resource_categories');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('resource_image')->nullable();
            $table->string('status')->comment('1 - active , 0 - inactive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_data');
    }
};
