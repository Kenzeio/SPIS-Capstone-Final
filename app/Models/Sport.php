<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;

    protected $table = 'sports';

    protected $fillable = [
        'name',
        'description',
    ];

    public function assignedSport()
    {
        return $this->hasMany(AssignedSports::class, 'sport_id'); 
    }

}
