<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Student;

class ResultsService
{
    protected $SubjectService;
    protected $StudentService;
    public function __construct(SubjectsService $SubjectService,StudentsService $StudentService){
        $this->SubjectService = $SubjectService;
        $this->StudentService = $StudentService;
    }

    public function addResults($studentId,$results)
    {
        $student = Student::where('user_id',$studentId)
            ->with('currentGrade','currentTerm')
            ->first();
        $subjects = $this->SubjectService->getSubjectsForStudent($student);

        foreach ($subjects as $subject){
            $score = $results[$subject->id]??null;
            if($score!==null){
                Result::updateOrCreate([
                    'student_id' => $student->user_id,
                    'subject_id' => $subject->id,
                    'term_id' => $student->current_term_id,
                    'grade_id' => $student->current_grade_id,
                ],[
                    'score' => $score,
                ]);
            }
        }
        $gradeBeforeUpgrade = $student->current_grade_id;
        $termBeforeUpgrade = $student->current_term_id;


        //upgrade action
        $this->StudentService->upgradeStudent($student);


        $studentWithResults = Student::where('user_id', $student->user_id)
            ->with([
                'currentGrade',
                'currentTerm',
                'results' => function ($query) use ($student,$gradeBeforeUpgrade,$termBeforeUpgrade) {
                    $query->where('term_id', $termBeforeUpgrade)
                        ->where('grade_id', $gradeBeforeUpgrade)
                        ->with('subject'); // Optionally load subject details
                }
            ])
            ->first();
        return $studentWithResults;
    }

    //Get results for a specific student based on grade and term.
    public function getResultsByGradeAndTerm($studentId, $gradeId, $termId)
    {
        // Fetch results that match the student, grade, and term IDs
        $results = Result::where('student_id', $studentId)
            ->where('grade_id', $gradeId)
            ->where('term_id', $termId)
            ->with('subject') // Load the related subject
            ->get();

        // Check if any results are found, return null if none
        if ($results->isEmpty()) {
            return null;
        }

        return $results;
    }


}
