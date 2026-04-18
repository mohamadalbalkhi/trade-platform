<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'referral_reward_level')) {
                $table->integer('referral_reward_level')->default(0)->after('referred_by');
            }

            if (!Schema::hasColumn('users', 'is_agent')) {
                $table->boolean('is_agent')->default(false)->after('referral_reward_level');
            }

            if (!Schema::hasColumn('users', 'weekly_profit_enabled')) {
                $table->boolean('weekly_profit_enabled')->default(false)->after('is_agent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'referral_reward_level')) {
                $columnsToDrop[] = 'referral_reward_level';
            }

            if (Schema::hasColumn('users', 'is_agent')) {
                $columnsToDrop[] = 'is_agent';
            }

            if (Schema::hasColumn('users', 'weekly_profit_enabled')) {
                $columnsToDrop[] = 'weekly_profit_enabled';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};