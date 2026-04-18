<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_strategies', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_strategies', 'redeem_requested_at')) {
                $table->timestamp('redeem_requested_at')->nullable();
            }

            if (!Schema::hasColumn('ai_strategies', 'redeem_approved_at')) {
                $table->timestamp('redeem_approved_at')->nullable();
            }

            if (!Schema::hasColumn('ai_strategies', 'redeem_status')) {
                $table->string('redeem_status')->default('none');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ai_strategies', function (Blueprint $table) {
            if (Schema::hasColumn('ai_strategies', 'redeem_requested_at')) {
                $table->dropColumn('redeem_requested_at');
            }

            if (Schema::hasColumn('ai_strategies', 'redeem_approved_at')) {
                $table->dropColumn('redeem_approved_at');
            }

            if (Schema::hasColumn('ai_strategies', 'redeem_status')) {
                $table->dropColumn('redeem_status');
            }
        });
    }
};