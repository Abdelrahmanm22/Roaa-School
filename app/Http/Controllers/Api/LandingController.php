<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddGuardianWithStudentsRequest;
use App\Http\Requests\CreateJobRequest;
use App\Models\Trip;
use App\Services\GuardiansService;
use App\Services\JobsService;
use App\Services\StudentsService;
use App\Services\UsersService;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LandingController extends Controller
{

    use ApiResponseTrait;

    protected $JobService;
    protected $GuardianService;
    protected $StudentService;
    protected $UsersService;
    public function __construct(GuardiansService $GuardianService,StudentsService $StudentService,JobsService $JobService,UsersService $UsersService)
    {
        $this->GuardianService = $GuardianService;
        $this->StudentService = $StudentService;
        $this->JobService = $JobService;
        $this->UsersService = $UsersService;
    }

    public function addGuardianWithStudents(AddGuardianWithStudentsRequest $request){
        try {
            DB::beginTransaction();
             $guardian = $this->GuardianService->createGuardian($request);
             $students = [];
             foreach ($request->input('students') as $student){
                 $newStudent = $this->StudentService->createStudent($student,$guardian->user_id);
                 $students[] = $newStudent;
             }

             DB::commit();
            return $this->apiResponse(['guardian' => $guardian, 'students' => $students], "Guardian and his students added successfully", 201);

        }
        catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to add guardian and students: ' . $e->getMessage());
            return $this->apiResponse(null, "Failed to add guardian and students: " . $e->getMessage(), 500);
        }
    }

    public function trips()
    {
        $trips = Trip::where('date', '>=', Carbon::now()->format('Y-m-d'))->orderBy('date', 'asc')->get();
        return $this->apiResponse($trips,"Get Trips Successfully",200);
    }

    public function createJob(CreateJobRequest $request)
    {
        $job = $this->JobService->createJob($request);
        return $this->apiResponse($job, "Job created successfully", 201);
    }
    public function checkPassportNumber(Request $request)
    {
        $request->validate([
            'passport_number' => 'required|string|max:50',
        ]);

        $exists = $this->UsersService->passportNumberExists($request->passport_number);

        if ($exists) {
            return $this->apiResponse(null, "Passport number already exists.", 409);
        }
        return $this->apiResponse(null, "Passport number is available.", 200);
    }
}
