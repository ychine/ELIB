<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Optional: Backfill new columns from old Publish_Date before dropping
        \DB::statement('UPDATE resources SET publish_year = YEAR(Publish_Date), publish_month = MONTH(Publish_Date), publish_day = DAY(Publish_Date) WHERE Publish_Date IS NOT NULL');

        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('Publish_Date');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->date('Publish_Date')->after('Type')->nullable();
        });

        // Optional: Restore from new columns
        \DB::statement('UPDATE resources SET Publish_Date = STR_TO_DATE(CONCAT(publish_year, "-", LPAD(publish_month, 2, "0"), "-", LPAD(publish_day, 2, "0")), "%Y-%m-%d") WHERE publish_year IS NOT NULL');
    }
};