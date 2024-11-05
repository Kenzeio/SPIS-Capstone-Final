<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'shortName', 'teamName', 'description'];

    // A college can have many assigned teams
    public function assignedTeams()
    {
        return $this->hasMany(AssignedTeams::class, 'college_id'); 
    }
}

