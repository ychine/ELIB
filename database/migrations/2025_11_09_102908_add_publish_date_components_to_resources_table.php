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
        Schema::table('resources', function (Blueprint $table) {
            $table->integer('publish_year')->nullable()->after('Publish_Date');
            $table->integer('publish_month')->nullable()->after('publish_year');
            $table->integer('publish_day')->nullable()->after('publish_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['publish_year', 'publish_month', 'publish_day']);
        });
    }
};