<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annotations', function (Blueprint $table) {
            $table->dropColumn(['published']);
        });
        Schema::table('annotation_types', function (Blueprint $table) {
            $table->dropColumn(['published']);
        });
        Schema::table('annotation_categories', function (Blueprint $table) {
            $table->dropColumn(['published']);
        });
    }

    public function down(): void
    {
        Schema::table('annotation_categories', function (Blueprint $table) {
            $table->boolean('published')->default(false);
        });
        Schema::table('annotation_types', function (Blueprint $table) {
            $table->boolean('published')->default(false);
        });
        Schema::table('annotations', function (Blueprint $table) {
            $table->boolean('published')->default(false);
        });
    }
};
