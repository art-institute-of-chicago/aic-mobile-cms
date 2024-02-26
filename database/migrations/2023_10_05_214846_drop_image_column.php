<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('collection_objects', function (Blueprint $table) {
            $table->dropColumn(['image_id']);
        });

        Schema::table('loan_objects', function (Blueprint $table) {
            $table->dropColumn(['image']);
        });
    }

    public function down(): void
    {
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->string('image')->nullable();
        });

        Schema::table('collection_objects', function (Blueprint $table) {
            $table->string('image_id')->nullable();
        });
    }
};
