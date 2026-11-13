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
        // 1. Tabel Fasilitas
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('category')->default('umum'); // kamar, umum, sekitar
            $table->timestamps();
        });

        // 2. Tabel Kos
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->char('province_id', 2);
            $table->char('regency_id', 4);
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['putra', 'putri', 'campur'])->default('campur');
            $table->decimal('price_start', 12, 2)->default(0);
            $table->enum('rent_type', ['bulanan', 'tahunan', 'harian'])->default('bulanan');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('rules')->nullable(); // Aturan kos
            $table->enum('verification_status', ['draft', 'pending', 'verified', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('cascade');

            $table->index('province_id');
            $table->index('regency_id');
            $table->index('type');
            $table->index('verification_status');
            $table->index('price_start');
        });

        // 3. Tabel Pivot Kos & Fasilitas
        Schema::create('kost_facilities', function (Blueprint $table) {
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->primary(['kost_id', 'facility_id']);
        });

        // 4. Tabel Kamar Kos
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->string('name'); // Misal: Tipe Standar, Tipe Deluxe
            $table->decimal('price', 12, 2);
            $table->integer('total_rooms')->default(1);
            $table->integer('available_rooms')->default(1);
            $table->string('room_size')->nullable(); // Misal: 3x4 meter
            $table->timestamps();
        });

        // 5. Tabel Foto Kos
        Schema::create('kost_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->string('file_path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kost_images');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('kost_facilities');
        Schema::dropIfExists('kosts');
        Schema::dropIfExists('facilities');
    }
};
