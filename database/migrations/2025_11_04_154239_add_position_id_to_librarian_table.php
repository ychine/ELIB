<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('librarian', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->constrained('librarian_positions')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('librarian', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });
    }
};