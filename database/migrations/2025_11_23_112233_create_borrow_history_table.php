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
        Schema::create('borrow_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrower_id');
            $table->unsignedBigInteger('user_id'); // The user who made the request
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('action_by')->nullable(); // Librarian/admin who performed the action
            $table->string('action'); // 'requested', 'approved', 'rejected', 'returned', 'cancelled'
            $table->text('rejection_reason')->nullable(); // Store rejection reason if action is 'rejected'
            $table->dateTime('approved_at')->nullable(); // When it was approved (with time)
            $table->dateTime('return_date')->nullable(); // Expected return date with time
            $table->dateTime('returned_at')->nullable(); // When it was actually returned (with time)
            $table->timestamps();

            $table->foreign('borrower_id')->references('Borrower_ID')->on('borrower')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('resource_id')->references('Resource_ID')->on('resources')->onDelete('cascade');
            $table->foreign('action_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_history');
    }
};
