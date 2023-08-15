<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoundsTables extends Migration
{
    public function up()
    {
        Schema::create('sounds', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('datahub_id');
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->string('transcript')->nullable();
        });
    }

    public function down()
    {

        Schema::dropIfExists('sounds');
    }
}
