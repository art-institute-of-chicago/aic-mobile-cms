<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Selector;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('selectors', function (Blueprint $table) {
            $table->boolean('published')->default(false);
        });
        Selector::query()->update(['published' => 1]);
    }

    public function down(): void
    {
        Schema::table('selectors', function (Blueprint $table) {
            $table->dropColumn('published');
        });
    }
};
