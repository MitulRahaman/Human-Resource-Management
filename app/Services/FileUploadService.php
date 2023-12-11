<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    class FileUploadService
    {
        public function setPath($photo)
        {
            if(is_array($photo)) {
                $names = array();
                foreach($photo as $p) {
                    $originalName = $p->getClientOriginalName();
                    $random = Str::random(25);
                    array_push($names,  $random.$originalName);
                }
                return $names;
            } else {
                $originalName = $photo->getClientOriginalName();
                $random = Str::random(25);
                return $random.$originalName;
            }
        }

        public function uploadFile($fileName, $photo)
        {
            if(is_array($photo)) {
                $i = 0;
                foreach($photo as $p) {
                    $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'leaveAppliedFiles';
                    $p->move($destinationPath, $fileName[$i++]);
                }
                return true;
            } else {
                $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'userImg';
                return $photo->move($destinationPath, $fileName);
            }

        }
    }
