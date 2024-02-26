<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('selectors', function (Blueprint $table) {
            $table->integer('number')->nullable()->change();
            $table->nullableMorphs('object');
        });
        Schema::table('stops', function (Blueprint $table) {
            $table->dropMorphs('object');
        });
        Schema::dropIfExists('stop_translations');
        Schema::dropIfExists('stop_revisions');
        Schema::table('collection_objects', function (Blueprint $table) {
            $table->dropColumn(['published']);
            $table->text('credit_line')->change();
        });
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->boolean('is_on_view')->nullable()->after('artist_display');
            $table->text('credit_line')->change();
        });
    }

    public function down(): void
    {
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->string('credit_line')->change();
            $table->dropColumn(['is_on_view']);
        });
        Schema::table('collection_objects', function (Blueprint $table) {
            $table->string('credit_line')->change();
            $table->boolean('published')->default(false);
        });
        Schema::create('stop_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'stop');
        });
        Schema::create('stop_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'stop');
            $table->string('title')->nullable();
        });
        Schema::table('stops', function (Blueprint $table) {
            $table->nullableMorphs('object');
        });
        Schema::table('selectors', function (Blueprint $table) {
            $table->dropMorphs('object');
            $table->integer('number')->nullable(false)->change();
        });
    }
};
