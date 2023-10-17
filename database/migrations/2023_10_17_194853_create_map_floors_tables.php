<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapFloorsTables extends Migration
{
    public function up()
    {
        Schema::create('map_floors', function (Blueprint $table) {
            createDefaultTableFields($table, published: false);
            $table->string('title');
            $table->string('anchor_pixel_1');
            $table->string('anchor_pixel_2');
            $table->string('anchor_location_1');
            $table->string('anchor_location_2');
        });
    }

    public function down()
    {
        Schema::dropIfExists('map_floors');
    }
}
