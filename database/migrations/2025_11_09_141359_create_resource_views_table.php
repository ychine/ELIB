<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->foreign('resource_id')->references('Resource_ID')->on('resources')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Ensure one record per user per resource
            $table->unique(['resource_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_views');
    }
};