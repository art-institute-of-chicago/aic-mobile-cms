<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            createDefaultTableFields($table, publishDates: true);
            $table->integer('position')->unsigned()->nullable();
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->integer('selector_number')->nullable();
            $table->string('gallery_id')->nullable()->comment('Datahub foreign key');
            $table->string('sound_id')->nullable()->comment('Datahub foreign key');
        });

        Schema::create('tour_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'tour');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('transcript')->nullable();
            $table->string('sound_id')->nullable()->comment('Datahub foreign key');
        });

        Schema::create('tour_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'tour');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_revisions');
        Schema::dropIfExists('tour_translations');
        Schema::dropIfExists('tours');
    }
};
