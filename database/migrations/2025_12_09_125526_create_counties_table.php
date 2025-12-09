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
        Schema::create('counties', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('siruta_code')->unique();
            $table->string('name');
            $table->string('name_ascii')->index();
            $table->string('slug')->unique()->index();
            $table->string('abbr', 2)->default(null);
            $table->unsignedTinyInteger('code'); // 1–42
            $table->string('region')->nullable();
            $table->timestamps();
            $table->index('code');
            $table->index('name');
            $table->index('abbr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counties');
    }
};
