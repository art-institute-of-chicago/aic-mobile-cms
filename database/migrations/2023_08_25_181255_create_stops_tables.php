<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up()
    {
        Schema::table('sounds', function (Blueprint $table) {
            $table->string('locale', 10)->change()->default(config('app.locale'));
            $table->text('transcript')->change()->nullable();
        });

        Schema::create('stops', function (Blueprint $table) {
            createDefaultTableFields($table, publishDates: true);
            $table->integer('selector_number')->unique();
            $table->string('artwork_id')->nullable()->comment('Datahub foreign key');
        });

        Schema::create('stop_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'stop');
            $table->string('title')->nullable();
        });

        Schema::create('stop_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'stop');
        });

        Schema::table('tour_stops', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Tour::class)->change()->constrained();
            $table->foreignIdFor(\App\Models\Stop::class)->constrained();
            $table->unique(['tour_id', 'stop_id']);
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('tour_stop_translations');
        Schema::dropIfExists('tour_stop_revisions');
    }

    public function down()
    {
        Schema::table('tour_stops', function (Blueprint $table) {
            $table->softDeletes();
            $table->dropForeignIdFor(\App\Models\Stop::class);
            $table->foreignIdFor(\App\Models\Tour::class)->change()->constrained(false);
            $table->dropUnique(['tour_id', 'stop_id']);
        });
        Schema::create('tour_stop_translations', function (Blueprint $table) {
            $table->id();
        });
        Schema::create('tour_stop_revisions', function (Blueprint $table) {
            $table->id();
        });

        Schema::dropIfExists('stop_revisions');
        Schema::dropIfExists('stop_translations');
        Schema::dropIfExists('stops');
    }
};
