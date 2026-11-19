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
        if (! Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('wallet_balance', 14, 2)->default(0)->after('vip_expires_at');
            });
        }

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reference_id', 100)->unique()->index();
            $table->string('type', 30)->index(); // topup, payment, withdrawal, refund
            $table->decimal('amount', 14, 2);
            $table->decimal('balance_before', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->string('status', 30)->default('pending')->index(); // pending, success, failed, rejected
            $table->string('description', 255);
            $table->string('payment_method', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('account_holder', 150)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');

        if (Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('wallet_balance');
            });
        }
    }
};
