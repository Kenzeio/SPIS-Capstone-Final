<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palakasan extends Model
{
    use HasFactory;

    protected $table = 'palakasans';

    protected $fillable = [
        'year',
        'theme',
        'tagline',
        'description',
        'start_date',
        'end_date',
        'status',
    ];
    protected $casts = [
        'year' => 'integer', // Cast year to integer
    ];

    public function assignedTeams()
    {
        return $this->hasMany(AssignedTeams::class, 'palakasan_id');
    }

    public function assignedSports()
    {
        return $this->hasMany(AssignedSports::class, 'palakasan_sport_id');
    }


}
