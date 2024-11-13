<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\TripImage;
use App\Models\TripVideo;
use App\Traits\AttachmentTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
class TripsService
{
    use AttachmentTrait;

    // Function to get trip by ID
    public function getTripById($tripId)
    {
        return Trip::with(['images', 'videos'])->find($tripId);
    }

    public function allTrips()
    {
        return Trip::with('images')->orderBy('date', 'desc')->get();
    }
    public function createTrip($data)
    {
        $date = Carbon::createFromFormat('d-m-Y', $data->input('date'))->format('Y-m-d'); // Convert to Y-m-d
        // Use Carbon to get the day name
        $dayName = Carbon::parse($date)->locale('ar')->dayName;

        $trip = Trip::create([
           'title'=>$data->title,
           'subtitle'=>$data->subtitle,
           'date'=>$date,
           'day'=>$dayName,
        ]);

        if ($data->has('images')) {
            foreach ($data->file('images') as $image)  {
                $imageName = $this->saveAttach($image, "Attachment/Trips/".$trip->id);
                TripImage::create([
                    'trip_id' => $trip->id,
                    'image' => $imageName,
                ]);
            }
        }
        // Save video links if provided
        if ($data->has('videos')) {
            foreach ($data->input('videos') as $videoLink) {
                TripVideo::create([
                    'trip_id' => $trip->id,
                    'link' => $videoLink,
                ]);
            }
        }

        return $trip;
    }
    // New function to delete trip and its image
    public function deleteTrip($tripId)
    {
        $trip = Trip::with('images')->find($tripId);

        if (!$trip) {
            return false; // Trip not found
        }

        // Check if the trip has an image and delete it
        if ($trip->images) {
            $imageFolderPath = public_path("Attachment/Trips/".$trip->id);
            if (File::exists($imageFolderPath)) {
                File::deleteDirectory($imageFolderPath); // Delete the entire directory
            }
        }

        $trip->delete();

        return true;
    }
    public function deleteTripImage($tripId, $imageId)
    {
        $tripImage = TripImage::where('trip_id', $tripId)->where('id', $imageId)->first();

        if (!$tripImage) {
            return false; // Image not found for this trip
        }

        // Define the image path
        $imagePath = public_path("Attachment/Trips/{$tripId}/" . $tripImage->image);

        // Check if the file exists and delete it
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the record from the database
        $tripImage->delete();

        return true;
    }
    public function deleteTripVideo($tripId, $videoId){
        $tripVideo = TripVideo::where('trip_id', $tripId)->where('id', $videoId)->first();
        if (!$tripVideo) {
            return false;
        }
        $tripVideo->delete();
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
