<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlayer extends Model
{
    use HasFactory;

    protected $table = 'student_players';

    protected $fillable = [
        'student_player_id',
        'student_assigned_sport_id'
    ];

    public function studentID()
    {
        return $this->belongsTo(Student::class, 'student_player_id');
    }
    public function assignedSport()
    {
        return $this->belongsTo(AssignedSports::class, 'student_assigned_sport_id');
    }
}
