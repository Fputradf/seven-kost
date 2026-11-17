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
        if (! Schema::hasColumn('users', 'vip_expires_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dateTime('vip_expires_at')->nullable()->after('status');
            });
        }

        if (! Schema::hasColumn('banners', 'payment_status')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->string('payment_status', 30)->default('paid')->after('is_active');
                $table->string('payment_channel', 50)->nullable()->after('payment_status');
                $table->decimal('amount', 12, 2)->default(0)->after('payment_channel');
            });
        }

        if (! Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('reference_id', 100)->unique();
                $table->enum('type', ['vip', 'banner'])->default('vip');
                $table->string('item_id', 100);
                $table->string('title');
                $table->decimal('amount', 12, 2);
                $table->string('payment_channel', 50)->default('qris');
                $table->enum('status', ['pending', 'paid', 'expired', 'failed'])->default('pending');
                $table->string('xendit_invoice_id', 150)->nullable();
                $table->string('xendit_invoice_url', 255)->nullable();
                $table->dateTime('paid_at')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();

                $table->index('reference_id');
                $table->index('status');
                $table->index('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');

        if (Schema::hasColumn('banners', 'payment_status')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn(['payment_status', 'payment_channel', 'amount']);
            });
        }

        if (Schema::hasColumn('users', 'vip_expires_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('vip_expires_at');
            });
        }
    }
};
