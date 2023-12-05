<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audios', function (Blueprint $table) {
            $table->string('datahub_id')->nullable()->change();
            $table->foreignId('selector_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('audios', function (Blueprint $table) {
            $table->string('datahub_id')->nullable(false)->change();
            $table->dropColumn(['selector_id']);
        });
    }
};
