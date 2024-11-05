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
        Schema::create('sports_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_sport_id')->constrained('assigned_sports')->onDelete('cascade'); // Foreign key to assigned_sports
            $table->string('sport_variation_name');
            $table->foreignId('sport_variation_venue_id')->constrained('venues')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_variations');
    }
};
