<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('front_image');
            $table->string('back_image');
            $table->string('selfie_image');

            $table->enum('status', [
                'pending',
                'verified',
                'rejected'
            ])->default('pending');

            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};