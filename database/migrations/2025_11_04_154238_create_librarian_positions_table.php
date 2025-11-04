<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('librarian_positions', function (Blueprint $table) {
            $table->bigIncrements('id'); // Explicit: bigint unsigned auto-increment
            $table->string('name');
            $table->json('permissions')->default('{"add": false, "archive": false, "delete": false}'); // JSON string default
            $table->unsignedBigInteger('created_by'); // Match users.id type
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('librarian_positions');
    }
};