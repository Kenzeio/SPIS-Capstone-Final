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
        Schema::create('sports_variations_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_variation_id')->constrained('sports_variations')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->foreignId('sport_variation_team_id')->constrained('assigned_teams')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->string('rank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_variations_matches');
    }
};
