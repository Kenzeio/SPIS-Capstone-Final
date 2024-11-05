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
        Schema::create('match_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_match_id')->constrained('sport_matches')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->integer('teamA_score');
            $table->integer('teamB_score');
            $table->foreignId('winning_team_id')->constrained('assigned_teams')->onDelete('cascade');
            $table->foreignId('losing_team_id')->constrained('assigned_teams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_results');
    }
};
