<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annotations', function (Blueprint $table) {
            $table->decimal('longitude', 16, 13)->change();
        });
        Schema::table('collection_objects', function (Blueprint $table) {
            $table->decimal('longitude', 16, 13)->change();
        });
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->decimal('longitude', 16, 13)->change();
        });
        Schema::table('galleries', function (Blueprint $table) {
            $table->decimal('longitude', 16, 13)->change();
        });
    }

    public function down(): void
    {
        Schema::table('annotations', function (Blueprint $table) {
            $table->decimal('longitude', 15, 13)->change();
        });
        Schema::table('collection_objects', function (Blueprint $table) {
            $table->decimal('longitude', 15, 13)->change();
        });
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->decimal('longitude', 15, 13)->change();
        });
        Schema::table('galleries', function (Blueprint $table) {
            $table->decimal('longitude', 15, 13)->change();
        });
    }
};
