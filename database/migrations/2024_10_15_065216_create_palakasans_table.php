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
        Schema::create('palakasans', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('theme');
            $table->string('tagline');
            $table->text('description'); // Corrected here
            $table->date('start_date'); 
            $table->date('end_date');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palakasans');
    }
};
