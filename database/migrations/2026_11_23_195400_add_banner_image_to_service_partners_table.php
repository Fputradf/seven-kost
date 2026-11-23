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
        Schema::table('service_partners', function (Blueprint $table) {
            $table->string('banner_image', 255)->nullable()->after('operational_hours');
        });
    }

    public function down(): void
    {
        Schema::table('service_partners', function (Blueprint $table) {
            $table->dropColumn('banner_image');
        });
    }
};
