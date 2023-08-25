<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::table('sounds', function (Blueprint $table) {
            $table->string('locale', 10);
            $table->text('transcript');
        });
    }

    public function down(): void
    {
        Schema::table('sounds', function (Blueprint $table) {
            $table->dropColumn('locale');
            $table->dropColumn('transcript');
        });
    }
};
