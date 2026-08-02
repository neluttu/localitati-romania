<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Deleting an account is irreversible from the owner's point of view but not
 * yet from ours: the row is marked instead of removed, which stops the login
 * and every API token at once while leaving a window to undo a mistake. A
 * scheduled purge removes the data for good once that window closes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->softDeletes();
        });

        Schema::table('sites', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });

        Schema::table('sites', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }
};
