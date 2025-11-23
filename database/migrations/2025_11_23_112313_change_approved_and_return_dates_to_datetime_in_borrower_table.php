<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrower', function (Blueprint $table) {
            $table->dateTime('Approved_Date')->nullable()->change();
            $table->dateTime('Return_Date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrower', function (Blueprint $table) {
            $table->date('Approved_Date')->nullable()->change();
            $table->date('Return_Date')->nullable()->change();
        });
    }
};
