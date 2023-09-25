<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('loan_objects', function (Blueprint $table) {
            createDefaultTableFields($table, published: false);
            $table->string('main_reference_number')->nullable();
            $table->string('title')->nullable();
            $table->string('artist_display')->nullable();
            $table->string('credit_line')->nullable();
            $table->string('copyright_notice')->nullable();
            $table->decimal('latitude', 15, 13)->nullable();
            $table->decimal('longitude', 15, 13)->nullable();
            $table->string('image')->nullable();
            $table->string('gallery_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_objects');
    }
};
