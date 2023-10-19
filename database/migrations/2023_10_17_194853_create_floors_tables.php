<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('floors', function (Blueprint $table) {
            createDefaultTableFields($table, published: false);
            $table->string('title');
        });
    }

    public function down()
    {
        Schema::dropIfExists('floors');
    }
};
