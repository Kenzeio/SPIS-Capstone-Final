<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportsVariationsMatches extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'sport_variation_id',
        'sport_variation_team_id',
        'rank',
    ];

    public function sportVariationID()
    {
        return $this->belongsTo(SportsVariations::class, 'sport_variation_id');
    }

    public function assignedTeamVariationID()
    {
        return $this->belongsTo(AssignedTeams::class, 'sport_variation_team_id');
    }
}
