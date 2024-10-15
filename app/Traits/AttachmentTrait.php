<?php

namespace App\Traits;

trait AttachmentTrait
{
    function saveAttach($file,$folderPath){
        $original_name = $file->getClientOriginalName();
        $attach_file_name = $original_name;
        $avatar_path = $folderPath;
        $file->move(public_path($avatar_path),$attach_file_name);
        return $attach_file_name;
    }
}
