<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::rename('artworks', 'collection_objects');
    }

    public function down(): void
    {
        Schema::rename('collection_objects', 'artworks');
    }
};
