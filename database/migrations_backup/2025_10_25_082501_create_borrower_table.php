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
        Schema::create('borrower', function (Blueprint $table) {
            $table->id('Borrower_ID');
            $table->unsignedBigInteger('UID');
            $table->unsignedBigInteger('Approved_By')->nullable();
            $table->date('Approved_Date')->nullable();
            $table->date('Return_Date')->nullable();
            $table->boolean('isReturned')->default(false);
            $table->foreign('UID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('Approved_By')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrower');
    }
};
