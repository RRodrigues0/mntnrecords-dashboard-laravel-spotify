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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->date('statement_date')->format('Y-m');
            $table->string('release_title');
            $table->string('barcode');
            $table->integer('track_downloads');
            $table->integer('track_streams');
            $table->integer('release_downloads');
            $table->decimal('track_downloads_revenue', 8, 2);
            $table->decimal('track_streams_revenue', 8, 2);
            $table->decimal('release_downloads_revenue', 8, 2);
            $table->decimal('total_revenue', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
