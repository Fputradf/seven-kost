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
        Schema::create('service_partner_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_partner_id')->constrained('service_partners')->cascadeOnDelete();
            $table->string('branch_name', 150);
            $table->string('phone', 30)->nullable();
            $table->string('province_id', 2)->nullable();
            $table->string('regency_id', 4)->nullable();
            $table->string('address', 255);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('service_radius_km', 5, 2)->default(5.00);
            $table->string('operational_hours', 100)->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamps();

            $table->index(['service_partner_id', 'regency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_partner_branches');
    }
};
