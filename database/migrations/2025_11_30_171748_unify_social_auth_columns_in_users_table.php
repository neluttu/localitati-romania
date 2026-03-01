<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Adăugăm `provider` imediat după `password`
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider')->nullable()->after('password');
            }
        });

        // 2. Renumim google_id în provider_id, dacă există
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->renameColumn('google_id', 'provider_id');
            }
        });

        // 3. Mutăm provider_id după provider
        Schema::table('users', function (Blueprint $table) {

            // Dacă provider_id nu există, îl creăm
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->after('provider');
                return;
            }

            // Dacă există și vrem să-l mutăm -> change()
            // NECESITĂ doctrine/dbal instalat
            $table->string('provider_id')->nullable()->change()->after('provider');
        });

        // 4. Ștergem coloana veche auth_provider
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'auth_provider')) {
                $table->dropColumn('auth_provider');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Punem înapoi auth_provider
            if (!Schema::hasColumn('users', 'auth_provider')) {
                $table->string('auth_provider')->nullable();
            }

            // Mutăm provider_id înapoi în google_id
            if (Schema::hasColumn('users', 'provider_id')) {
                $table->renameColumn('provider_id', 'google_id');
            }

            // Ștergem provider
            if (Schema::hasColumn('users', 'provider')) {
                $table->dropColumn('provider');
            }
        });
    }

};
