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
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Insider One League');
            $table->unsignedTinyInteger('current_week')->default(0); // 0 = not started
            $table->unsignedTinyInteger('total_weeks')->default(6);  // 4 teams double round-robin => 6
            $table->boolean('is_started')->default(false);
            $table->boolean('is_finished')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leagues');
    }
};
