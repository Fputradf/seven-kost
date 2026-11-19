<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $defaults = [
            [
                'key' => 'rent_bill_notice_days',
                'value' => '7',
                'group' => 'rental',
                'type' => 'number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'late_fee_enabled',
                'value' => '1',
                'group' => 'rental',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'late_fee_grace_days',
                'value' => '1',
                'group' => 'rental',
                'type' => 'number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'late_fee_daily_amount',
                'value' => '20000',
                'group' => 'rental',
                'type' => 'number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($defaults as $setting) {
            DB::table('app_settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('app_settings')->whereIn('key', [
            'rent_bill_notice_days',
            'late_fee_enabled',
            'late_fee_grace_days',
            'late_fee_daily_amount',
        ])->delete();
    }
};
