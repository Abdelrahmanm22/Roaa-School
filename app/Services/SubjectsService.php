<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
class SubjectsService
{

    public function getSubjectById($subjectId)
    {
        return Subject::find($subjectId);
    }


    public function getSubjectsForGradeAndTerm(int $gradeId, int $termId)
    {

        // Using query builder to get subjects for the specified grade and term
        $subjects = DB::table('subjects')
            ->join('subject_term', 'subjects.id', '=', 'subject_term.subject_id')
            ->where('subject_term.grade_id', $gradeId)
            ->where('subject_term.term_id', $termId)
            ->select('subjects.id', 'subjects.name') // select specific columns
            ->get();

        return $subjects;
    }





    ///retrieves the subjects of a student's current grade and term,
    /// along with the total number of videos and the number of videos the student has watched for each subject.
    public function getSubjectsWithVideoStatsForStudent(Student $student)
    {
        // Get the student's current grade and term
        $gradeId = $student->current_grade_id;
        $termId = $student->current_term_id;


        $subjects = DB::table('subjects')
            ->join('subject_term', 'subjects.id', '=', 'subject_term.subject_id')
            ->leftJoin('videos', function ($join) use ($gradeId, $termId) {
                $join->on('subjects.id', '=', 'videos.subject_id')
                    ->where('videos.grade_id', '=', $gradeId)
                    ->where('videos.term_id', '=', $termId);
            })
            ->leftJoin('student_video', function ($join) use ($student) {
                $join->on('videos.id', '=', 'student_video.video_id')
                    ->where('student_video.student_id', '=', $student->user_id);
            })
            ->where('subject_term.grade_id', $gradeId)
            ->where('subject_term.term_id', $termId)
            ->select(
                'subjects.id',
                'subjects.name',
                DB::raw('COUNT(DISTINCT videos.id) as total_videos'),
                DB::raw('COUNT(DISTINCT CASE WHEN student_video.watched = 1 THEN videos.id END) as watched_videos')
            )
            ->groupBy('subjects.id', 'subjects.name')
            ->get();

        return $subjects;
    }


    //retrieve the subjects for a specific student based on their current grade and term:
    public function getSubjectsForStudent($student)
    {
        return $student->currentTerm->subjects()
            ->wherePivot('grade_id', $student->current_grade_id)
            ->get();
    }
}
