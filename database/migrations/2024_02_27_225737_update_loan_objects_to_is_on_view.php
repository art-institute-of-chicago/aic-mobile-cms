<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\LoanObject;

return new class extends Migration
{
    public function up(): void
    {
        LoanObject::query()->update(['is_on_view' => 1]);
    }

    public function down(): void
    {
        LoanObject::query()->update(['is_on_view' =>  null]);
    }
};
