<?php

namespace App\Services;

use App\Models\Trip;
use App\Traits\AttachmentTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
class TripsService
{
    use AttachmentTrait;

    // Function to get trip by ID
    public function getTripById($tripId)
    {
        return Trip::find($tripId);
    }

    public function allTrips()
    {
        return Trip::orderBy('date', 'desc')->get();
    }
    public function createTrip($data)
    {
        $date = $data->input('date');
        // Use Carbon to get the day name
        $dayName = Carbon::parse($date)->locale('ar')->dayName;

        $imageName = null;
        if($data->image){
            $imageName = $this->saveAttach($data->image,"Attachment/Trips/");
        }
        $trip = Trip::create([
           'title'=>$data->title,
           'subtitle'=>$data->subtitle,
           'date'=>$data->date,
           'day'=>$dayName,
            'image'=>$imageName,
        ]);
        return $trip;
    }
    // New function to delete trip and its image
    public function deleteTrip($tripId)
    {
        $trip = Trip::find($tripId);

        if (!$trip) {
            return false; // Trip not found
        }

        // Check if the trip has an image and delete it
        if ($trip->image) {
            $imagePath = public_path("Attachment/Trips/{$trip->image}");
            if (File::exists($imagePath)) {
                File::delete($imagePath); // Delete the image file
            }
        }

        $trip->delete();

        return true;
    }
    public function nextTrips()
    {
        return Trip::nextTrips();
    }

    public function nextTrip()
    {
        return Trip::nextTrip();
    }

}
