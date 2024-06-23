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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->unique();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->binary('avatar')->nullable();
            $table->timestamps();
        });

        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->string('isrc')->unique();
            $table->string('barcode')->unique()->nullable();
            $table->string('title');
            $table->integer('percentage')->default(50);
            $table->decimal('income', 8, 2)->default(0);
            $table->boolean('reserved')->default(true);
            $table->timestamps();
        });

        Schema::create('splits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('release_id')->references('id')->on('releases');
            $table->foreignId('artist_id')->references('id')->on('artists');
            $table->integer('percentage')->default(50);
            $table->decimal('income', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->date('statement_date')->format('Y-m');
            $table->decimal('income', 8, 2)->default(0);
            $table->foreignId('split_id')->references('id')->on('splits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');

        Schema::table('splits', function (Blueprint $table) {
            $table->dropForeign(['release_id']);
            $table->dropForeign(['artist_id']);
        });
        Schema::dropIfExists('splits');

        Schema::table('artists', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('artists');

        Schema::dropIfExists('releases');
    }
};
