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
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'tenant_nik')) {
                $table->string('tenant_nik', 16)->nullable()->after('tenant_phone');
            }
            if (! Schema::hasColumn('bookings', 'tenant_gender')) {
                $table->string('tenant_gender', 20)->nullable()->after('tenant_nik'); // pria, wanita
            }
            if (! Schema::hasColumn('bookings', 'tenant_job')) {
                $table->string('tenant_job', 100)->nullable()->after('tenant_gender');
            }
            if (! Schema::hasColumn('bookings', 'emergency_phone')) {
                $table->string('emergency_phone', 30)->nullable()->after('tenant_job');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['tenant_nik', 'tenant_gender', 'tenant_job', 'emergency_phone'] as $col) {
                if (Schema::hasColumn('bookings', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
