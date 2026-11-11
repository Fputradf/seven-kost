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
        Schema::create('marketplace_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('perabot')->index();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('condition')->default('bekas_baik');
            $table->string('meetup_location');
            $table->string('city')->nullable()->index();
            $table->text('description');
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status')->default('active')->index(); // active, sold, archived
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });

        Schema::create('marketplace_item_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_item_id')->constrained('marketplace_items')->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_item_images');
        Schema::dropIfExists('marketplace_items');
    }
};
