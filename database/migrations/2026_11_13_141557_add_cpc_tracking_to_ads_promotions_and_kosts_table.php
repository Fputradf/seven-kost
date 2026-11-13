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
        Schema::table('ads_promotions', function (Blueprint $table) {
            $table->integer('click_count')->default(0)->after('status');
            $table->decimal('cpc_spent', 12, 2)->default(0)->after('click_count');
        });

        Schema::table('kosts', function (Blueprint $table) {
            $table->integer('ad_clicks_count')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads_promotions', function (Blueprint $table) {
            $table->dropColumn(['click_count', 'cpc_spent']);
        });

        Schema::table('kosts', function (Blueprint $table) {
            $table->dropColumn(['ad_clicks_count']);
        });
    }
};
