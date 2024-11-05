<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportMatch extends Model
{
    protected $fillable = [
        'assigned_sport_id',
        'teamA_id',
        'teamB_id',
        'round',
        'game',
        'bracket_type',
        'elimination_type',
        'status',
        'match_venue_id',
        'date',
        'time'
    ];

    public function matchVenue()
    {
        return $this->belongsTo(Venue::class, 'match_venue_id');
    }
    
    public function assignedSport()
    {
        return $this->belongsTo(AssignedSports::class, 'assigned_sport_id');
    }

    public function teamA()
    {
        return $this->belongsTo(AssignedTeams::class, 'teamA_id');
    }

    public function teamB()
    {
        return $this->belongsTo(AssignedTeams::class, 'teamB_id');
    }

    public function match_result()
    {
        return $this->hasOne(MatchResult::class, 'sport_match_id');
    }



}