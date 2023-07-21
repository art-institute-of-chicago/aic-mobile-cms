<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            createDefaultTableFields($table);
            $table->string('datahub_id');
            $table->string('title')->nullable();
            $table->string('artist_display')->nullable();
            $table->boolean('is_on_view')->nullable();
            $table->string('credit_line')->nullable();
            $table->string('copyright_notice')->nullable();
            $table->decimal('latitude', 15, 13)->nullable();
            $table->decimal('longitude', 15, 13)->nullable();
            $table->string('image_id')->nullable();
            $table->string('gallery_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
