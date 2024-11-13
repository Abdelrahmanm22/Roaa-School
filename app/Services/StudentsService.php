<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Guardian;
use App\Models\Setting;
use App\Models\Student;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
class StudentsService
{
    protected $UserService;
    protected $VideoService;

    public function __construct(UsersService $UserService,VideosService $VideoService){
        $this->UserService = $UserService;
        $this->VideoService = $VideoService;
    }
    public function getStudentsByGradeAndTerm($gradeId, $termId)
    {
        return Student::where('current_grade_id', $gradeId)
            ->where('current_term_id', $termId)
            ->where('status','مقبول')
            ->with('user')
            ->get();
    }

    public function getAcceptedStudents()
    {
        return DB::table('students as s')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->join('grades as g', 's.current_grade_id', '=', 'g.id')
            ->join('guardians as gu', 's.guardian_id', '=', 'gu.user_id') // Join with the guardians table
            ->join('users as guardian_user', 'gu.user_id', '=', 'guardian_user.id') // Join to get the guardian's user details
            ->select(
                'u.id as user_id',
                'u.name',
                'u.passport_number',
                's.code',
                's.birthdate',
                's.phone',
                'g.name as current_grade',
                'guardian_user.id as parent_id', // Guardian's ID
                'guardian_user.name as parent_name' // Guardian's name
            )
            ->where('s.status', 'مقبول')
            ->get();
    }

    public function createStudent($student,$guardian_id)
    {
        $user = $this->UserService->createUser($student,'student');
        return Student::create([
            'user_id' => $user->id,
            'guardian_id' => $guardian_id,
            'birthdate'=>$student['birthdate'],
            'gender'=>$student['gender'],
            'SSN'=>$student['SSN'],
            'phone'=>$student['phone'],
            'rank'=>$student['rank'],
            'relationship'=>$student['relationship'],
            'current_grade_id'=>$student['current_grade_id'],
            'current_term_id'=>1,
        ]);
    }

    //return student with Accepted Status
    public function countAcceptedStudents()
    {
        return Student::where('status', 'مقبول')->count();
    }

    //return students with pending status
    public function countNewStudents()
    {
        return Student::where('status','قيد الانتظار')->count();
    }

    public function getStudent($studentId)
    {
        return Student::where('user_id', $studentId)->first();
    }


    //changes the student's status and, if the status is set to 'مقبول', generates a student code using the generateStudentCode function.
    public function changeStudentStatus($studentId,$status){
        $student = Student::where('user_id',$studentId)->first();
        if (!$student || $student->status == 'مقبول' || $student->status == 'مرفوض') {
            return null;
        }

        $student->update(['status' => $status]);
        // If the status is 'مقبول', generate a student code and update the student
        if ($status == 'مقبول') {
            $studentCode = $this->generateStudentCode();
            $student->update(['code' => $studentCode]);

            $videos = $this->VideoService->getVideosForGradeTerm($student->current_grade_id, $student->current_term_id);
            foreach ($videos as $video) {
                $this->VideoService->addStudentVideo($student, $video);
            }
            $student->grades()->attach($student->current_grade_id);
            $student->terms()->attach($student->current_term_id);

        }
        return $student;
    }

    //function to generate code for students
    public function generateStudentCode(){
        $academicYear = Setting::where('key', 'academic_year')->first()->value;
        $yearSuffix = substr($academicYear, -2);
        $lastCode = Setting::where('key', 'last_student_code')->first()->value;

        //Generate the new student code
        $nextNumber = (int)$lastCode + 1;
        $newCode = 'STU' . $yearSuffix . $nextNumber;

        DB::table('settings')
            ->where('key', 'last_student_code')
            ->update(['value' => $nextNumber]);
        return $newCode;
    }

    public function getGuardianStudentsWithCurrentGradeAndTerm($guardianId)
    {
        return Guardian::with(['students' => function ($query) {
            $query->where('status', 'مقبول')
                ->with(['currentGrade', 'currentTerm']);
        }])
            ->where('user_id', $guardianId)
            ->first();
    }

    public function upgradeStudent($student)
    {
        $currentGradeId = $student->current_grade_id;
        $currentTermId = $student->current_term_id;

        $lastYearId = Grade::where('name','الصف الثالث الثانوي')->first()->id;
        if ($currentTermId==1){
            // لو الترم الاول
            $student->update(['current_term_id' => $currentTermId+1]);
            $student->terms()->attach($student->current_term_id);
            $videos = $this->VideoService->getVideosForGradeTerm($student->current_grade_id, $student->current_term_id);
            foreach ($videos as $video) {
                $this->VideoService->addStudentVideo($student, $video);
            }
        }else if($currentTermId==2 and $lastYearId==$currentGradeId){
            //graduated
            $student->upedate(['current_term_id' => null,'current_grade_id'=>null]);
            $this->changeStudentStatus($student->user_id,"متخرج");

        }else if($currentTermId==2){
            //لو الترم التاني
            $student->update([
                'current_term_id' => 1,
                'current_grade_id'=>$currentGradeId+1
            ]);
            $student->grades()->attach($student->current_grade_id);
            $student->terms()->attach($student->current_term_id);
            $videos = $this->VideoService->getVideosForGradeTerm($student->current_grade_id, $student->current_term_id);
            foreach ($videos as $video) {
                $this->VideoService->addStudentVideo($student, $video);
            }
        }
    }
    public function getGradesAndTermsForStudent($studentId)
    {
        // Retrieve the student with grades and their associated terms
        $student = Student::with(['grades.terms'])->where('user_id',$studentId)->first();

        // Format the result as needed
        $result = [];
        foreach ($student->grades as $grade) {
            $result[] = [
                'grade' => $grade->name, // Assuming 'name' is the field for the grade name
                'grade_id' => $grade->id,
                'terms' => $grade->terms->map(function ($term) {
                    return $term->name; // Assuming 'name' is the field for the term name
                }),
            ];
        }

        return $result;
    }

    public function AddStudentOutsideTheSchool($student)
    {
        $user = $this->UserService->createUser($student,'student');
        $user->update(['email'=>$student['email']]); //الطالب اللي من برا المدرسه يعمل login بالايميل
        return Student::create([
            'user_id' => $user->id,
            'guardian_id' => null,
            'birthdate'=>$student['birthdate'],
            'gender'=>$student['gender'],
            'SSN'=>$student['SSN'],
            'phone'=>$student['phone'],
            'rank'=>null,
            'relationship'=>null,
            'current_grade_id'=>Grade::where('name','الصف الثالث الثانوي')->first()->id, //هيكون في تالته ثانوي بس
            'current_term_id'=>1,
            'status'=>'خارج المدرسه',
        ]);
    }

}
