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
        Schema::create('website_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('content_image')->nullable();
            $table->integer('status')->comment('0 = inactive, 1 = active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_page_contents');
    }
};
