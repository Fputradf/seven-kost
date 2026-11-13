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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active')->after('avatar');
            $table->timestamp('suspended_until')->nullable()->after('status');
            $table->text('moderation_reason')->nullable()->after('suspended_until');
            $table->timestamp('moderated_at')->nullable()->after('moderation_reason');
            $table->foreignId('moderated_by')->nullable()->after('moderated_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn([
                'status',
                'suspended_until',
                'moderation_reason',
                'moderated_at',
                'moderated_by',
            ]);
        });
    }
};
