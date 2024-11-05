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
        Schema::create('sport_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_sport_id')->constrained('assigned_sports')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->foreignId('teamA_id')->nullable()->constrained('assigned_teams')->onDelete('cascade'); // Foreign key to assigned_teams
            $table->foreignId('teamB_id')->nullable()->constrained('assigned_teams')->onDelete('cascade'); // Foreign key to assigned_teams
            $table->string('game');//if it is game 1, game 2, to track the game progression and which game is the first one
            $table->string('status');// value of this would be 'not started', 'on going' and 'completed'
            $table->string('round');
            $table->string('bracket_type');
            $table->string('elimination_type');
            $table->foreignId('match_venue_id')->constrained('venues')->onDelete('cascade'); // Foreign key to assigned_teams
            $table->date('date')->nullable(); // New column for the date of the match
            $table->string('time')->nullable(); // New column for the time of the match
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sport_matches');
    }
};
