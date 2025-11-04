<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('librarian_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('permissions')->default(['add' => false, 'archive' => false, 'delete' => false]);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('librarian_positions');
    }
};