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
        Schema::create('businesses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('alias');
            $table->string('name');
            $table->string('image_url')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_closed')->default(1);
            $table->string('review_count')->nullable();
            $table->foreignUuid('categories_id')->nullable();
            $table->string('rating')->nullable();
            $table->foreignUuid('coordinates_id');
            $table->json('transactions')->nullable();
            $table->string('price')->nullable();
            $table->foreignUuid('location_id');
            $table->string('phone');
            $table->string('display_phone');
            $table->string('distance')->nullable();
            $table->timestamps();

            $table->foreign('categories_id')->references('id')->on('categories');
            $table->foreign('coordinates_id')->references('id')->on('coordinates');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
