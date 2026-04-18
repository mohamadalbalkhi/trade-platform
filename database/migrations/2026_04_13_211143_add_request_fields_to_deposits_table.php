<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('deposits', 'deposit_id')) {
                $table->string('deposit_id')->nullable()->unique()->after('user_id');
            }

            if (!Schema::hasColumn('deposits', 'requested_amount')) {
                $table->decimal('requested_amount', 18, 2)->nullable()->after('amount');
            }

            if (!Schema::hasColumn('deposits', 'request_note')) {
                $table->string('request_note')->nullable()->after('requested_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (Schema::hasColumn('deposits', 'request_note')) {
                $table->dropColumn('request_note');
            }

            if (Schema::hasColumn('deposits', 'requested_amount')) {
                $table->dropColumn('requested_amount');
            }

            if (Schema::hasColumn('deposits', 'deposit_id')) {
                $table->dropUnique(['deposit_id']);
                $table->dropColumn('deposit_id');
            }

            if (Schema::hasColumn('deposits', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};