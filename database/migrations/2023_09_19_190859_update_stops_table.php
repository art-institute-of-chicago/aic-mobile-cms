<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->dropColumn('artwork_id');
            $table->nullableMorphs('object');
        });
    }

    public function down(): void
    {
        Schema::table('stops', function (Blueprint $table) {
            $table->string('artwork_id')->nullable()->comment('Datahub foreign key');
            $table->dropColumns(['object_id', 'object_type']);
        });
    }
};
