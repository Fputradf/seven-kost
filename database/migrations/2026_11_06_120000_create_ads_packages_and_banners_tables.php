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
        // 1. Paket Iklan Unggulan (Atur Tarif oleh Superadmin)
        Schema::create('ads_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration_days')->default(7);
            $table->decimal('price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Transaksi Promosi Iklan (Dicatat Manual oleh Admin)
        Schema::create('ads_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->foreignId('ads_package_id')->constrained('ads_packages')->onDelete('cascade');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'rejected'])->default('pending');
            $table->string('proof_of_payment')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('kost_id');
            $table->index('status');
        });

        // 3. Banner Halaman Depan
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path');
            $table->string('target_url')->nullable();
            $table->char('regency_id', 4)->nullable(); // Null = Nasional
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->integer('click_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
        Schema::dropIfExists('ads_promotions');
        Schema::dropIfExists('ads_packages');
    }
};
