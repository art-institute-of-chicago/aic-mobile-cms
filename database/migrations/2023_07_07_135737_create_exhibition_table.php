<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exhibitions', function (Blueprint $table) {
            createDefaultTableFields($table, true, false);
            $table->string('datahub_id');
            $table->string('title')->nullable();
            $table->string('image_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exhibitions');
    }
};
