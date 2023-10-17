<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelsTables extends Migration
{
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            createDefaultTableFields($table, published: false);
            $table->string('key');
        });

        Schema::create('label_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'label');
            $table->text('text')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('label_translations');
        Schema::dropIfExists('labels');
    }
}
