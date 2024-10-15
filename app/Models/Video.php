<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'link',
        'subject_id',
        'term_id',
        'grade_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }


    // Relationship with students through the student_video pivot table
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_video', 'video_id', 'student_id')
            ->withPivot('watched', 'created_at', 'updated_at');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function scopeForGradeTermAndSubject($query, $gradeId, $termId, $subjectId)
    {
        return $query->where('grade_id', $gradeId)
            ->where('term_id', $termId)
            ->where('subject_id', $subjectId)
            ->select('id','name');
    }
    public function scopeForGradeTerm($query, $gradeId, $termId){
        return $query->where('grade_id', $gradeId)
            ->where('term_id', $termId)
            ->select('id','name');
    }
}
