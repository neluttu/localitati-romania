<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siruta_code')->unique();
            $table->foreignId('county_id')->constrained('counties')->cascadeOnDelete();

            $table->string('name');
            $table->string('name_ascii');
            $table->string('type')->nullable(); // city, commune, village, town etc.
            $table->string('postal_code')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->timestamps();

            $table->index('county_id');
            $table->index('name');
            $table->index('postal_code');
            $table->index('type');
            $table->index('name_ascii');
            $table->index(['lat', 'lng']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
