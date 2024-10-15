<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function terms()
    {
        // Define the relationship with the Term model through the subject_term table
        return $this->belongsToMany(Term::class)
            ->withPivot('grade_id'); // Include the grade_id in the pivot table
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    protected $hidden = [
        'created_at',  // Hide created_at
        'updated_at',  // Hide updated_at
    ];
}
