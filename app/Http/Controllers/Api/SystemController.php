<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddGuardianWithStudentsRequest;
use App\Services\GuardiansService;
use App\Services\StudentsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    use ApiResponseTrait;

    protected $GuardianService;
    protected $StudentService;
    public function __construct(GuardiansService $GuardianService,StudentsService  $StudentService)
    {
        $this->GuardianService = $GuardianService;
        $this->StudentService = $StudentService;
    }

    ///add guardian with students without get approvement from admin
    public function addGuardianWithStudents(AddGuardianWithStudentsRequest $request)
    {
        try{
            DB::beginTransaction();

            //create Guardian
            $guardian = $this->GuardianService->createGuardian($request);

            //Create Students that have passwords and i will set status 'مقبول' for them
            $students = [];
            foreach ($request->input('students') as $student) {
                $student['status'] = 'مقبول';
                $newStudent = $this->StudentService->createStudent($student, $guardian->user_id);

                //Generate student code for each student
                $studentCode = $this->StudentService->generateStudentCode();
                $newStudent->update(['code' => $studentCode]);

                //store newStudent in array
                $students[] = $newStudent;
            }

            DB::commit();
            return $this->apiResponse(['guardian' => $guardian, 'students' => $students],'Guardian and his students added successfully',201);
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::error('Failed to add guardian and students: ' . $exception->getMessage());
            return $this->apiResponse(null, "Failed to add guardian and students: " . $exception->getMessage(), 500);
        }
    }
    public function getParentsWithPendingStudents()
    {
        $parents = $this->GuardianService->getGuardiansWithPendingDataStudents();
        return $this->apiResponse($parents,"Guardians with pending students retrieved successfully",200);
    }
    public function upgradeStudentInSystem($studentId)
    {
        try {
            DB::beginTransaction();

            // Get student
            $student = $this->StudentService->getStudent($studentId);
            if (!$student) {
                return $this->apiResponse(null, "Student not found", 404);
            }

            // Upgrade student
            $this->StudentService->upgradeStudent($student);

            DB::commit();
            return $this->apiResponse($student, "Student upgraded successfully", 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error('Failed to upgrade student: ' . $exception->getMessage());
            return $this->apiResponse(null, "Failed to upgrade student: " . $exception->getMessage(), 500);
        }
    }

}
