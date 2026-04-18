<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_strategies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('user_name');
            $table->string('pair');

            $table->double('amount');
            $table->double('percent');

            $table->double('profit')->default(0);

            $table->string('status')->default('executing');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_strategies');
    }
};