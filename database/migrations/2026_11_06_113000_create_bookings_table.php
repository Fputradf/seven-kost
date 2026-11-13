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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Penghuni
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('duration_months')->default(1);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('tenant_name')->nullable();
            $table->string('tenant_phone')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('kost_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
