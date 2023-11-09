<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    class FileUploadService
    {
        public function setPath($photo)
        {
            $originalName = $photo->getClientOriginalName();
            $random = Str::random(25);
            return $random.$originalName;
        }

        public function uploadFile($fileName, $photo)
        {
            $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'userImg';
            return $photo->move($destinationPath, $fileName);
        }
    }