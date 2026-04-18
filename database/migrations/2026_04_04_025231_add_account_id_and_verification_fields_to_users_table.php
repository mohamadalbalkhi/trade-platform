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
        if (!Schema::hasColumn('users', 'account_id')) {

            Schema::table('users', function (Blueprint $table) {
                // Account ID (4 digit random)
                $table->string('account_id', 4)->unique()->nullable()->after('id');
            });

        }

        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'verification_status')) {
                $table->enum('verification_status', [
                    'unverified',
                    'pending',
                    'verified',
                    'rejected',
                ])->default('unverified');
            }

            if (!Schema::hasColumn('users', 'id_front_image')) {
                $table->string('id_front_image')->nullable();
            }

            if (!Schema::hasColumn('users', 'id_back_image')) {
                $table->string('id_back_image')->nullable();
            }

            if (!Schema::hasColumn('users', 'selfie_with_id_image')) {
                $table->string('selfie_with_id_image')->nullable();
            }

            if (!Schema::hasColumn('users', 'preferred_language')) {
                $table->string('preferred_language')->default('en');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'account_id')) {
                $table->dropColumn('account_id');
            }

            if (Schema::hasColumn('users', 'verification_status')) {
                $table->dropColumn('verification_status');
            }

            if (Schema::hasColumn('users', 'id_front_image')) {
                $table->dropColumn('id_front_image');
            }

            if (Schema::hasColumn('users', 'id_back_image')) {
                $table->dropColumn('id_back_image');
            }

            if (Schema::hasColumn('users', 'selfie_with_id_image')) {
                $table->dropColumn('selfie_with_id_image');
            }

            if (Schema::hasColumn('users', 'preferred_language')) {
                $table->dropColumn('preferred_language');
            }

        });
    }
};