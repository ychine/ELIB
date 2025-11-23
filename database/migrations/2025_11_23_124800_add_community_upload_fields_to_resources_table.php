<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->boolean('is_community_upload')->default(false)->after('Type');
            $table->unsignedBigInteger('owner_id')->nullable()->after('Uploaded_By');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['owner_id']);
            $table->dropColumn(['is_community_upload', 'owner_id']);
        });
    }
};
