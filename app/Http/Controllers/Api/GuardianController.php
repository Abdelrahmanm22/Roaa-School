<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\ResultsService;
use App\Services\StudentsService;
use App\Services\TripsService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class GuardianController extends Controller
{
    use ApiResponseTrait;
    protected $TripService;
    protected $StudentService;
    protected $ResultService;

    public function __construct(TripsService $TripService,StudentsService $StudentService,ResultsService $ResultService)
    {
        $this->TripService=$TripService;
        $this->StudentService=$StudentService;
        $this->ResultService=$ResultService;
    }
    public function getStudentsWithDetails($guardianId)
    {
        $students = $this->StudentService->getGuardianStudentsWithCurrentGradeAndTerm($guardianId);

        if (!$students) {
            return $this->apiResponse(null,"no students found",404);
        }

        return $this->apiResponse($students,"Get Students Successfully",200);
    }

    public function loginToChild($child_id)
    {
        $child = $this->StudentService->getStudent($child_id);
        if (!$child){
            return $this->apiResponse(null,"child not found",404);
        }

        $token = JWTAuth::fromUser($child->user);
        $student = [
            'token' => $token,
            'child' => $child,
        ];
        return $this->apiResponse($student,"You have successfully logged in.",200);
    }

    public function trips()
    {
        $trips = $this->TripService->nextTrips()->get();
        return $this->apiResponse($trips,'Get Next Trips Successfully',200);
    }

    //get grades and his terms (الحضرهم في المدرسه)
    public function getGradesAndHisTermsForStudent($studentId)
    {
//        return $id;
        $student = Student::where('user_id',$studentId)->first();
        if(!$student){
            return $this->apiResponse(null,"student not found",404);
        }
        $gradesAndTerms = $this->StudentService->getGradesAndTermsForStudent($studentId);
        return $this->apiResponse($gradesAndTerms,'Get Grades and his Terms',200);
    }

    //Get results for a specific student based on grade and term.
    public function getResultsByGradeAndTerm($studentId,$gradeId,$termId)
    {
        $results = $this->ResultService->getResultsByGradeAndTerm($studentId, $gradeId, $termId);
        if ($results) {
            return $this->apiResponse($results,"Get Results Successfully", 200);
        } else {
            return $this->apiResponse(null,"There are no results for this term yet.",404);
        }
    }

}
