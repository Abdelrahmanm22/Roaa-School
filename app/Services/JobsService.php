<?php

namespace App\Services;

use App\Models\Job;
use App\Traits\AttachmentTrait;

class JobsService
{
    use AttachmentTrait;

    //return number of jobs with status 'new'
    public function numberOfNewApplications()
    {
        return Job::where('status','جديدة')->count();
    }
    public function createJob($data)
    {
        $resume = $this->saveAttach($data->resume,"Attachment/Resumes/");

        return Job::create([
            'name'=>$data->name,
            'email'=>$data->email,
            'message'=>$data->message,
            'phone'=>$data->phone,
            'resume'=>$resume,
            'title'=>$data->title,
        ]);

    }

    public function markJobAsOpen($id)
    {
        $job = Job::find($id);
        if ($job) {
            $job->status = 'مفتوحة';
            $job->save();
            return $job;
        }
        return null;
    }
    public function getJob($id)
    {
        return Job::find($id);
    }
    public function getAllJobsDesc()
    {
        return Job::orderBy('created_at', 'desc')->get();
    }


}
