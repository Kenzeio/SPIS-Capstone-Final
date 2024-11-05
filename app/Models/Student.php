<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'univ_email',
        'college',
        'contact',
    ];
    public function studentAccount()
    {
        return $this->hasOne(StudentAccount::class, 'student_id'); 
    }

    public function studentID()
    {
        return $this->hasOne(StudentPlayer::class, 'student_player_id'); 
    }
}
