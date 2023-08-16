<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTables extends Migration
{
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('title')->nullable();
            $table->integer('position')->unsigned()->nullable();
            $table->string('artwork_id')->nullable()->comment('Datahub foreign key');
            $table->string('sound_id')->nullable()->comment('Datahub foreign key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stops');
    }
}
