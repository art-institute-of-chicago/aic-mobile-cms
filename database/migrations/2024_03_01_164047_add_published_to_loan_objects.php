<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\LoanObject;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->boolean('published')->default(false);
        });
        LoanObject::query()->update(['published' => 1]);
    }

    public function down(): void
    {
        Schema::table('loan_objects', function (Blueprint $table) {
            $table->dropColumn('published');
        });
    }
};
