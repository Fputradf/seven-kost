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
        Schema::create('service_partner_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_partner_id')->constrained('service_partners')->cascadeOnDelete();
            $table->string('name', 150);
            $table->unsignedBigInteger('price');
            $table->string('unit', 30)->default('/paket');
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['service_partner_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_partner_packages');
    }
};
