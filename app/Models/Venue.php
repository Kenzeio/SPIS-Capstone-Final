<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'description',
        'location'
    ];

    public function matchVenue()
    {
        return $this->hasMany(SportMatch::class, 'match_venue_id'); 
    }

    public function venue_id()
    {
        return $this->hasMany(SportsVariations::class, 'sport_variation_venue_id'); 
    }
}
