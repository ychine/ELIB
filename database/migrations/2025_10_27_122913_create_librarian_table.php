<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('librarian', function (Blueprint $table) {
            $table->unsignedBigInteger('UID')->primary();
            $table->string('First_Name');
            $table->string('Last_Name');
            $table->timestamps();

            $table->foreign('UID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('librarian');
    }
};