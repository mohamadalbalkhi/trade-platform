<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_strategies', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_strategies', 'strategy_name')) {
                $table->string('strategy_name')->nullable()->after('user_name');
            }

            if (!Schema::hasColumn('ai_strategies', 'target_pair')) {
                $table->string('target_pair')->nullable()->after('strategy_name');
            }

            if (!Schema::hasColumn('ai_strategies', 'amount')) {
                $table->decimal('amount', 16, 2)->default(0)->after('target_pair');
            }

            if (!Schema::hasColumn('ai_strategies', 'target_percent')) {
                $table->decimal('target_percent', 8, 2)->default(0)->after('amount');
            }

            if (!Schema::hasColumn('ai_strategies', 'lock_hours')) {
                $table->integer('lock_hours')->default(24)->after('target_percent');
            }

            if (!Schema::hasColumn('ai_strategies', 'risk_level')) {
                $table->string('risk_level')->nullable()->after('lock_hours');
            }

            if (!Schema::hasColumn('ai_strategies', 'status')) {
                $table->string('status')->default('pending')->after('risk_level');
            }

            if (!Schema::hasColumn('ai_strategies', 'order_no')) {
                $table->string('order_no')->nullable()->after('status');
            }

            if (!Schema::hasColumn('ai_strategies', 'current_profit')) {
                $table->decimal('current_profit', 16, 2)->default(0)->after('order_no');
            }

            if (!Schema::hasColumn('ai_strategies', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('current_profit');
            }

            if (!Schema::hasColumn('ai_strategies', 'unlock_at')) {
                $table->timestamp('unlock_at')->nullable()->after('started_at');
            }

            if (!Schema::hasColumn('ai_strategies', 'redeem_requested_at')) {
                $table->timestamp('redeem_requested_at')->nullable()->after('unlock_at');
            }

            if (!Schema::hasColumn('ai_strategies', 'redeem_available_at')) {
                $table->timestamp('redeem_available_at')->nullable()->after('redeem_requested_at');
            }

            if (!Schema::hasColumn('ai_strategies', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('redeem_available_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ai_strategies', function (Blueprint $table) {
            $columns = [
                'strategy_name',
                'target_pair',
                'amount',
                'target_percent',
                'lock_hours',
                'risk_level',
                'status',
                'order_no',
                'current_profit',
                'started_at',
                'unlock_at',
                'redeem_requested_at',
                'redeem_available_at',
                'closed_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('ai_strategies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};