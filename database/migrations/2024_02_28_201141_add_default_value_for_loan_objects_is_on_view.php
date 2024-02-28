<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->boolean('is_on_view')->default(true)->change();
        });
    }

    public function down(): void
    {
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->boolean('is_on_view')->default(null)->change();
        });
    }
};
