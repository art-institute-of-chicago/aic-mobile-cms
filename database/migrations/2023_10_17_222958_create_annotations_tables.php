<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('annotation_categories', function (Blueprint $table) {
            createDefaultTableFields($table);
        });

        Schema::create('annotation_category_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'annotation_category');
            $table->string('title')->nullable();
        });

        Schema::create('annotation_types', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->foreignId('annotation_category_id')->nullable();
        });

        Schema::create('annotation_type_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'annotation_type');
            $table->string('title')->nullable();
        });

        Schema::create('annotation_annotation_type', function (Blueprint $table) {
            $table->foreignId('annotation_id');
            $table->foreignId('annotation_type_id');
            $table->integer('position')->nullable()->comment('Unused but needed by Twill');
        });

        Schema::create('annotations', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->foreignId('floor_id')->nullable();
            $table->decimal('latitude', 15, 13)->nullable();
            $table->decimal('longitude', 15, 13)->nullable();
        });

        Schema::create('annotation_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'annotation');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
        });

        Schema::table('floors', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->string('level', 2);
            $table->string('geo_id')->comment('Reference to geojson data');
        });

        Schema::create('floor_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'floor');
            $table->string('title')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('annotation_category_translations');
        Schema::dropIfExists('annotation_categories');
        Schema::dropIfExists('annotation_type_translations');
        Schema::dropIfExists('annotation_types');
        Schema::dropIfExists('floor_translations');
        Schema::table('floors', function (Blueprint $table) {
            $table->dropColumn(['level', 'geo_id']);
            $table->string('title');
        });
        Schema::dropIfExists('annotation_translations');
        Schema::dropIfExists('annotations');
    }
};
