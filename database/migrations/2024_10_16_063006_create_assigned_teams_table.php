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
        Schema::create('assigned_teams', function (Blueprint $table) {
            $table->id();
            $table->string('assigned_team_name');
            $table->foreignId('college_id')->constrained()->onDelete('cascade'); // Foreign key to colleges table
            $table->foreignId('palakasan_id')->constrained()->onDelete('cascade'); // Foreign key to colleges table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_teams');
    }
};
