<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddResultsRequest;
use App\Http\Requests\AddStudentRequest;
use App\Http\Requests\AddTripRequest;
use App\Http\Requests\UpdateStudentStatusRequest;
use App\Models\Student;
use App\Services\GuardiansService;
use App\Services\JobsService;
use App\Services\ResultsService;
use App\Services\StudentsService;
use App\Services\SubjectsService;
use App\Services\TripsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataEntryController extends Controller
{
    use ApiResponseTrait;
    protected $TripService;
    protected $JobService;
    protected $StudentService;
    protected $GuardianService;
    protected $SubjectService;
    protected $ResultService;
    public function __construct(TripsService $TripService,JobsService $JobService,StudentsService $StudentService,GuardiansService $GuardianService,SubjectsService $SubjectService,ResultsService  $ResultService)
    {
        $this->TripService = $TripService;
        $this->JobService = $JobService;
        $this->StudentService = $StudentService;
        $this->GuardianService = $GuardianService;
        $this->SubjectService = $SubjectService;
        $this->ResultService = $ResultService;
    }


    public function addTrip(AddTripRequest  $request)
    {

        $trip = $this->TripService->createTrip($request);
        if ($trip)return $this->apiResponse($trip,"Add New Trip Successfully",201);
        return $this->apiResponse(0,"size of array equal 0",200);
    }

    public function deleteTrip($id)
    {
        $deleted = $this->TripService->deleteTrip($id);
        if ($deleted) {
            return $this->apiResponse(null, "Trip deleted successfully", 200);
        }
        return $this->apiResponse(null, "Trip not found", 404);
    }
    public function deleteTripImage($tripId, $imageId){
        $deleted = $this->TripService->deleteTripImage($tripId, $imageId);
        if ($deleted) {
            return $this->apiResponse(null, "Trip image deleted successfully", 200);
        }else{
            return $this->apiResponse(null, "Trip image not found", 404);
        }
    }
    public function deleteTripVideo($tripId, $videoId)
    {
        $deleted = $this->TripService->deleteTripVideo($tripId, $videoId);
        if ($deleted) {
            return $this->apiResponse(null, "Trip video deleted successfully", 200);
        }else{
            return $this->apiResponse(null, "Trip video not found", 404);
        }
    }

    public function markJobOpen($id)
    {

        $job = $this->JobService->markJobAsOpen($id);
        if ($job) {
            return $this->apiResponse($job, "Job status updated to 'مفتوحة'", 200);
        }
        return $this->apiResponse(null, "Job not found", 404);
    }
    public function getAllJobs()
    {
        $jobs = $this->JobService->getAllJobsDesc();
        return $this->apiResponse($jobs, "Jobs retrieved successfully", 200);
    }
    public function getJob($id)
    {
        $job = $this->JobService->getJob($id);
        if ($job) {
            return $this->apiResponse($job, "Job retrieved successfully", 200);
        }
        return $this->apiResponse(null, "Job not found", 404);
    }

    //number of applications
    public function applications()
    {
        $numberOfApplications = $this->JobService->numberOfNewApplications();
        return $this->apiResponse($numberOfApplications, "Number of applications retrieved successfully", 200);
    }


    public function newStudents(){
        $numberOfNewStudents = $this->StudentService->countNewStudents();
        return $this->apiResponse($numberOfNewStudents, "Number of students retrieved successfully", 200);
    }

    public function acceptedStudents()
    {
        $numberOfAcceptedStudents = $this->StudentService->countAcceptedStudents();
        return $this->apiResponse($numberOfAcceptedStudents, "Number of accepted students retrieved successfully", 200);
    }


    ///function retrieves guardians who have students with a status of "قيد الانتظار" (which means "Pending" in English),
    public function getParents()
    {
        $parents = $this->GuardianService->getGuardiansWithPendingStudents();
        return $this->apiResponse($parents, "Guardians with pending students retrieved successfully", 200);
    }
    ///function retrieves recent guardians who have students with a status of "قيد الانتظار" (which means "Pending" in English),
    public function getRecentParents(){
        $parents = $this->GuardianService->getRecentGuardiansWithPendingStudents();
        return $this->apiResponse($parents,"Recent Guardians with pending students retrieved successfully", 200);
    }

    ///function retrieves guardians who have students with a status of "مقبول" (which means "Accepted" in English),
    public function getParentsWithAcceptedStudents()
    {
        $parents = $this->GuardianService->getGuardiansWithAcceptedStudents();
        return $this->apiResponse($parents,"Recent Guardians with accepted students retrieved successfully", 200);
    }

    public function getParent($id)
    {
        $parent = $this->GuardianService->getGuardianByIdWithPendingStudentsAndDetails($id);
        return $this->apiResponse($parent, "Guardian retrieved successfully", 200);
    }

    public function updateStudentStatus(UpdateStudentStatusRequest $request, $studentId)
    {
        $student = $this->StudentService->changeStudentStatus($studentId,$request->status);
        if ($student==null){
            return $this->apiResponse(null, "Student not found or already accepted or rejected", 400);
        }
        return $this->apiResponse($student, "Student status updated successfully", 200);
    }

    public function getSubjectsForStudent($studentId)
    {
        $student = Student::where('user_id', $studentId)->first();
        if(!$student){
            return $this->apiResponse(null, "Student not found", 404);
        }
        $subjects = $this->SubjectService->getSubjectsForStudent($student);
        return $this->apiResponse($subjects, "Subjects retrieved successfully", 200);
    }

    public function getStudentsByGradeAndTerm($gradeId, $termId){
        $students = $this->StudentService->getStudentsByGradeAndTerm($gradeId, $termId);
        return $this->apiResponse($students, "Students retrieved successfully", 200);
    }

    public function addResults(AddResultsRequest $request)
    {
        $studentWithResults = $this->ResultService->addResults(
            $request->student_id,
            $request->results,
        );
        return $this->apiResponse($studentWithResults,"Result added successfully and Upgrade for student done!", 201);
    }
    //function to Add a Student Outside The School
    public function addStudent(AddStudentRequest $request){
        // Call the service to add the student outside the school
        $student = $this->StudentService->AddStudentOutsideTheSchool($request->all());
        // Return the student in the API response
        return $this->apiResponse($student, 'Student added successfully outside the school.', 201);
    }

    public function getAcceptedStudents()
    {
        $students = $this->StudentService->getAcceptedStudents();
        return $this->apiResponse($students, "Accepted students retrieved successfully", 200);
    }
}
