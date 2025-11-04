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

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_university_librarian')->default(false)->after('role');
        });
    }

    public function down()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['status', 'author']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_university_librarian');
        });
    }
};