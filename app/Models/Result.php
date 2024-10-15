<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'subject_id', 'term_id','grade_id','score'];

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    protected $hidden = [
        'created_at',  // Hide created_at
        'updated_at',  // Hide updated_at
    ];
}
