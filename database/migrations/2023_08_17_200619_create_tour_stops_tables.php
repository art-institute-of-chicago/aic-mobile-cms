<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up()
    {
        Schema::dropIfExists('stops');

        Schema::create('tour_stops', function (Blueprint $table) {
            createDefaultTableFields($table, published: false);
            $table->integer('position')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->foreignIdFor(\App\Models\Tour::class);
            $table->string('artwork_id')->nullable()->comment('Datahub foreign key');
        });

        Schema::create('tour_stop_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'tour_stop');
            $table->string('title')->nullable();
            $table->text('transcript')->nullable();
            $table->string('sound_id')->nullable()->comment('Datahub foreign key');
        });

        Schema::create('tour_stop_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'tour_stop');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_stop_revisions');
        Schema::dropIfExists('tour_stop_translations');
        Schema::dropIfExists('tour_stops');
    }
};
