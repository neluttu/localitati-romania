<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The domain on a site is a label its owner uses to tell their own sites
 * apart, not a claim on that domain. A global unique index made the first
 * person to register "example.com" the only one who ever could - every
 * registration after that hit an integrity violation and surfaced as a 500.
 * Uniqueness belongs per owner, which is what the form request already assumed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table): void {
            $table->dropUnique(['domain']);
            $table->unique(['user_id', 'domain']);
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table): void {
            $table->dropUnique(['user_id', 'domain']);
            $table->unique(['domain']);
        });
    }
};
