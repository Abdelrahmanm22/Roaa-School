<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubjectsService;
use App\Services\TripsService;
use App\Services\VideosService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    use ApiResponseTrait;

    protected $SubjectService;
    protected $TripService;
    protected $VideoService;
    public function __construct(SubjectsService $SubjectService,TripsService $TripService,VideosService $VideoService)
    {
        $this->SubjectService = $SubjectService;
        $this->TripService = $TripService;
        $this->VideoService = $VideoService;
    }


    ///function to get next controller only
    public function trip()
    {
        $trip = $this->TripService->nextTrip();
        return $this->apiResponse($trip,"Get Next Trip Successfully",200);
    }
    public function getTrip($id)
    {
        $trip = $this->TripService->getTripById($id);

        if ($trip) {
            return $this->apiResponse($trip, "Trip found", 200);
        }

        return $this->apiResponse(null, "Trip not found", 404);
    }

    public function getStudentSubjectsWithVideos()
    {
        // Get the currently logged-in student
        $user = auth()->user();
        if ($user->role=='admin'){
            return $this->apiResponse(null,'Hello Admin, please login with student account to get data',403);
        }

        $student = $user->student;

        // Retrieve subjects with video stats for the student
        $subjects = $this->SubjectService->getSubjectsWithVideoStatsForStudent($student);

        return $this->apiResponse($subjects,"Get Subjects with number of videos successfully",200);
    }

    public function getVideos($subject_id)
    {
        $user = auth()->user();
        if ($user->role == 'admin') {
            return $this->apiResponse(null, 'Hello Admin, please login with student account to get data', 403);
        }

        $student = $user->student;

        // Retrieve the subject name
        $subject = $this->SubjectService->getSubjectById($subject_id);
        if (!$subject) {
            return $this->apiResponse(null, "Subject not found", 404);
        }

        // Retrieve the videos for the student, grade, term, and subject
        $videos = $this->VideoService->getVideosForSubjectForStudent(
            $student->current_grade_id,
            $student->current_term_id,
            $subject_id
        );

        $response = [
            'subject_name' => $subject->name,
            'videos' => $videos
        ];

        return $this->apiResponse($response, "Videos retrieved successfully", 200);
    }


    public function mark($id)
    {
        $user = auth()->user();
        if ($user->role=='admin'){
            return $this->apiResponse(null,'Hello Admin, please login with student account to get data',403);
        }
        $student = $user->student;

        // Check if the video exists in the database
        $videoExists = $this->VideoService->checkVideoExists($id);
        if (!$videoExists) {
            return $this->apiResponse(null, 'Video not found', 404);
        }
        $this->VideoService->markVideoAsWatched($student,$id);
        return $this->apiResponse(null, "Mark video as watched successfully", 200);
    }

    public function getVideo($id)
    {
        $video = $this->VideoService->getVideo($id);
        if ($video){
            return $this->apiResponse($video, "Get Video successfully", 200);
        }
        return $this->apiResponse(null, "Video not found", 404);
    }
}
