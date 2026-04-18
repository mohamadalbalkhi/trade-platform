<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_wallets', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default('Main Admin Wallet');

            $table->string('wallet_address')->unique();

            $table->text('private_key')->nullable();

            $table->string('network')->default('TRC20');

            $table->boolean('is_active')->default(true);

            $table->boolean('is_main')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_wallets');
    }
};