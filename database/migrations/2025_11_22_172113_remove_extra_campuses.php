<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Campus;

return new class extends Migration
{
    public function up(): void
    {
        Campus::whereNotIn('Campus_Name', ['Echague', 'Santiago'])->delete();
    }

    public function down(): void
    {
        // Optionally re-add them
    }
};