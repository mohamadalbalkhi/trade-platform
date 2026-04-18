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

            // عنوان السحب
            $table->string('withdraw_wallet_address')->nullable()->after('password');

            // الشبكة (TRC20)
            $table->string('withdraw_wallet_network')->default('TRC20')->after('withdraw_wallet_address');

            // وقت قفل العنوان (متى تم حفظه)
            $table->timestamp('withdraw_wallet_locked_at')->nullable()->after('withdraw_wallet_network');

            // كلمة مرور التداول
            $table->string('trading_password')->nullable()->after('withdraw_wallet_locked_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'withdraw_wallet_address',
                'withdraw_wallet_network',
                'withdraw_wallet_locked_at',
                'trading_password'
            ]);

        });
    }
};