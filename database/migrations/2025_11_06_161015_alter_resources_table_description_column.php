<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->text('Description')->change();  // Change to TEXT (or longText() for even longer)
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('Description', 255)->change();  // Revert to original if needed
        });
    }
};