<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('selectors', function (Blueprint $table) {
            createDefaultTableFields($table, published: false);
            $table->nullableMorphs('selectable');
            $table->integer('number');
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selectors');
    }
};
