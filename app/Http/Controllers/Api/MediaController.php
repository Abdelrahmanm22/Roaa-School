<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVideoRequest;
use App\Models\Grade;
use App\Services\SubjectsService;
use App\Services\VideosService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{

    use ApiResponseTrait;
    protected $VideoService;
    protected $SubjectService;
    public function __construct(VideosService $VideoService,SubjectsService $SubjectService)
    {
        $this->VideoService = $VideoService;
        $this->SubjectService = $SubjectService;
    }

    //function to get all grades to use it in filtration
    public function grades()
    {
        $grades =Grade::get();
        return $this->apiResponse($grades,"Get Grades Successfully",200);
    }

    public function createVideo(CreateVideoRequest $request)
    {
        $video = $this->VideoService->createVideo($request);
        return $this->apiResponse($video,"Video created and assigned to students successfully",201);
    }

    //Get Subjects By Grade and term
    public function getSubjects(Request $request)
    {
        // Call the service to get subjects
        $subjects = $this->SubjectService->getSubjectsForGradeAndTerm($request->grade_id, $request->term_id);
        // Return the subjects in the API response
        return $this->apiResponse($subjects, 'Subjects retrieved successfully',200);
    }

    public function deleteVideo($id)
    {

        $result = $this->VideoService->deleteVideo($id);
        if ($result) {
            return $this->apiResponse($result,"Video deleted successfully.",200);
        } else {
            return $this->apiResponse(null,"Video not found or could not be deleted.",404);
        }
    }


}
