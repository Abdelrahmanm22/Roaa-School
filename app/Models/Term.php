<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'grade_term');
    }
    public function subjects()
    {
        // Define the relationship with the Subject model through the subject_term table
        return $this->belongsToMany(Subject::class)
            ->withPivot('grade_id'); // Include the grade_id in the pivot table
    }
    // Many-to-many relationship with students
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_term');
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
