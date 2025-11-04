<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('campus', function (Blueprint $table) {
            $table->string('Campus_Name')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('campus', function (Blueprint $table) {
            $table->dropUnique(['Campus_Name']);
        });
    }
};