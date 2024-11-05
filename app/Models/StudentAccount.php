<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAccount extends Model
{
    use HasFactory;

    protected $table = 'student_accounts';

    protected $fillable = [
        'student_id',
        'role',
        'status',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }


}
