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
        Schema::create('service_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('business_name', 150);
            $table->string('category', 50)->default('laundry'); // laundry, cleaning, catering, maintenance
            $table->char('province_id', 2)->nullable();
            $table->char('regency_id', 4)->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('service_radius_km', 5, 2)->default(5.00);
            $table->boolean('is_open')->default(true);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 100)->nullable();
            $table->string('bank_account_holder', 150)->nullable();
            $table->string('verification_status', 30)->default('verified'); // verified, pending, suspended
            $table->string('operational_hours', 100)->default('08:00 - 20:00 WIB');
            $table->string('banner_image', 255)->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->nullOnDelete();
            $table->foreign('regency_id')->references('id')->on('regencies')->nullOnDelete();
            $table->index('category');
            $table->index('is_open');
            $table->index('verification_status');
        });

        if (Schema::hasTable('transactions') && ! Schema::hasColumn('transactions', 'partner_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('partner_id')->nullable()->after('user_id')->constrained('service_partners')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions') && Schema::hasColumn('transactions', 'partner_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign(['partner_id']);
                $table->dropColumn('partner_id');
            });
        }

        Schema::dropIfExists('service_partners');
    }
};
