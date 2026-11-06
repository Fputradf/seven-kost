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
        Schema::create('provinces', function (Blueprint $table) {
            $table->char('id', 2)->primary(); // Kode BPS / Kemendagri 2 digit
            $table->string('name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });

        Schema::create('regencies', function (Blueprint $table) {
            $table->char('id', 4)->primary(); // Kode BPS / Kemendagri 4 digit
            $table->char('province_id', 2);
            $table->string('name');
            $table->string('type', 20)->default('Kabupaten'); // 'Kabupaten' atau 'Kota'
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->index('province_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regencies');
        Schema::dropIfExists('provinces');
    }
};

