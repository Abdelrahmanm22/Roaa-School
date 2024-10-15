<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends User
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'code',
        'SSN',
        'birthdate',
        'gender',
        'rank',
        'relationship',
        'phone',
        'status',
        'guardian_id',
        'current_grade_id',
        'current_term_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id','user_id');
    }
    //Many-to-many relationship: A student can attend multiple grades, and a grade can have multiple students.
    public function grades()
    {
//        return $this->belongsToMany(Grade::class, 'grade_student');
        return $this->belongsToMany(Grade::class, 'grade_student', 'student_id', 'grade_id','user_id', 'id');
    }

    //one-to-many relationship: A student has a single current grade, and a grade can be assigned to many students.
    public function currentGrade()
    {
        return $this->belongsTo(Grade::class, 'current_grade_id');
    }
    public function currentTerm()
    {
        return $this->belongsTo(Term::class, 'current_term_id');
    }

    public function watchedVideos()
    {
        return $this->belongsToMany(Video::class, 'student_video', 'student_id', 'video_id', 'user_id', 'id')
            ->withPivot('watched');
    }
    // Many-to-many relationship with terms
    public function terms()
    {
        return $this->belongsToMany(Term::class, 'student_term', 'student_id', 'term_id','user_id', 'id');
//        return $this->belongsToMany(Term::class, 'student_term');
    }

    public function results()
    {
        return $this->hasMany(Result::class,'student_id','user_id');
    }
}
