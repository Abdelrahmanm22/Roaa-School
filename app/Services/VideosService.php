<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class VideosService
{
    public function getAllVideosWithDetails()
    {
        return DB::table('videos')
            ->join('subjects', 'videos.subject_id', '=', 'subjects.id')
            ->join('terms', 'videos.term_id', '=', 'terms.id')
            ->join('grades', 'videos.grade_id', '=', 'grades.id')
            ->select(
                'videos.id',
                'videos.name as name',
                'videos.link',
                'subjects.name as subject_name',
                'terms.name as term_name',
                'grades.name as grade_name'
            )
            ->orderByDesc('videos.created_at')
            ->get();
    }
    public function getVideo($id)
    {
        return Video::find($id);
    }

    public function createVideo($data)
    {
        $video = Video::create([
           'name'=>$data->name,
           'link'=>$data->link,
           'subject_id'=>$data->subject_id,
            'term_id'=>$data->term_id,
            'grade_id'=>$data->grade_id,
        ]);

        //Retrieve all students who are in the specified grade and term
        $students = Student::where('current_grade_id',$data->grade_id)
            ->where('current_term_id',$data->term_id)
            ->where('status',"مقبول")
            ->get();

        //Loop through each student and add the video to their watched list
        foreach ($students as $student) {
           $this->addStudentVideo($student, $video, false);
        }

        return $video;
    }
    public function addStudentVideo($student, $video, $watched = false)
    {
        // Add the relationship in the student_video pivot table
        $student->watchedVideos()->attach($video->id, ['watched' => $watched]);
    }

    //Function to update watched status for a student-video entry
    public function markVideoAsWatched($student, $videoID)
    {
        // Update the watched status in the pivot table
        $student->watchedVideos()->updateExistingPivot($videoID, ['watched' => true]);
    }

    // Check if a video with the given ID exists in the video table
    public function checkVideoExists($videoID)
    {
        return Video::where('id', $videoID)->exists();
    }

    public function getVideosForSubjectForStudent($gradeId, $termId, $subjectId)
    {
        return Video::forGradeTermAndSubject($gradeId, $termId, $subjectId)->get();
    }


    //function to get videos for Full semester
    public function getVideosForGradeTerm($gradeId, $termId){
        return Video::forGradeTerm($gradeId, $termId)->get();
    }
    public function deleteVideo($videoId)
    {
        // Retrieve the video by its ID
        $video = Video::find($videoId);
        if (!$video) {
            return false;
        }
        // Detach any relationships in the pivot table (e.g., student_video)
        $video->students()->detach();
        // Delete the video from the database
        return $video->delete();
    }

}
