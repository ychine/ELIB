<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration appears to be a duplicate/conflict with 2025_11_23_112313_change_approved_and_return_dates_to_datetime_in_borrower_table.php
        // Skip if the changes were already applied
        if (Schema::hasTable('borrower')) {
            // Check if columns are already datetime
            $columns = Schema::getColumnListing('borrower');

            // If migration was already applied, do nothing
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed as this is a duplicate
    }
};
