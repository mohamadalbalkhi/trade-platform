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
            $table->string('deposit_trc20_address')->nullable()->unique()->after('google2fa_confirmed_at');
            $table->text('deposit_trc20_private_key')->nullable()->after('deposit_trc20_address');
            $table->boolean('deposit_trc20_active')->default(true)->after('deposit_trc20_private_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'deposit_trc20_address',
                'deposit_trc20_private_key',
                'deposit_trc20_active',
            ]);
        });
    }
};