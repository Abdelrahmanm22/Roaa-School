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
    public function __construct(GuardiansService $GuardianService,StudentsService $StudentService,JobsService $JobService)
    {
        $this->GuardianService = $GuardianService;
        $this->StudentService = $StudentService;
        $this->JobService = $JobService;
    }

    public function addGuardianWithStudents(AddGuardianWithStudentsRequest $request){
        try {
            DB::beginTransaction();
             $guardian = $this->GuardianService->createGuardian($request);
             $students = [];
             foreach ($request->input('students') as $student){
                 $students[] = $this->StudentService->createStudent($student,$guardian->user_id);
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
}
