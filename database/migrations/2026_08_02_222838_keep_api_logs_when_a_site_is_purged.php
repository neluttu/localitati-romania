<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * api_logs.site_id cascaded on delete, so purging an account would have taken
 * its traffic history with it and left the aggregate usage figures wrong for
 * every past month. Detaching the row instead keeps the call counts while
 * severing the link to the person who made them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_logs', function (Blueprint $table): void {
            $table->dropForeign(['site_id']);
            $table->foreignId('site_id')->nullable()->change();
            $table->foreign('site_id')->references('id')->on('sites')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('api_logs', function (Blueprint $table): void {
            $table->dropForeign(['site_id']);
            $table->foreignId('site_id')->nullable(false)->change();
            $table->foreign('site_id')->references('id')->on('sites')->cascadeOnDelete();
        });
    }
};
