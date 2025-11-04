<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('status')->default('Available');
            $table->string('author')->nullable();
        });

        // REMOVED: No more is_university_librarian – use positions instead
        // Schema::table('users', function (Blueprint $table) {
        //     $table->boolean('is_university_librarian')->default(false)->after('role');
        // });
    }

    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['status', 'author']);
        });

        // REMOVED: No down for the boolean
    }
};