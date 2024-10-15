<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    //many to many
    public function terms()
    {
        return $this->belongsToMany(Term::class, 'grade_term');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'grade_student');
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function currentStudents()
    {
        return $this->hasMany(Student::class, 'current_grade_id');
    }
    protected $hidden = [
        'created_at',  // Hide created_at
        'updated_at',  // Hide updated_at
    ];
}
