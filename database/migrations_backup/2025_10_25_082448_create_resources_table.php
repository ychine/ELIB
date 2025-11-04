<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id('Resource_ID');
            $table->string('Resource_Name');
            $table->string('File_Path');
            $table->enum('Type', ['Featured', 'Community Uploads']);
            $table->date('Upload_Date');
            $table->unsignedBigInteger('Uploaded_By');
            $table->foreign('Uploaded_By')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
