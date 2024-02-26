<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['sound_id']);
        });
        Schema::table('tour_translations', function (Blueprint $table) {
            $table->dropColumn(['sound_id', 'transcript']);
        });
        Schema::table('tour_stops', function (Blueprint $table) {
            $table->dropColumn(['artwork_id', 'title']);
        });
    }

    public function down(): void
    {
        Schema::table('tour_stops', function (Blueprint $table) {
            $table->string('artwork_id')->nullable();
            $table->string('title')->nullable();
        });
        Schema::table('tour_translations', function (Blueprint $table) {
            $table->string('sound_id')->nullable();
            $table->text('transcript')->nullable();
        });
        Schema::table('tours', function (Blueprint $table) {
            $table->string('sound_id')->nullable();
        });
    }
};
