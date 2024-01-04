<?php

    namespace App\Services;

    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Config;

    class FileUploadService
    {
        private $pathName;

        public function setPathName($pathName)
        {
            $this->pathName = $pathName;
            return $this;
        }

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
            if($this->pathName == Config::get('variable_constants.file_path.user')) {
                $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'userImg';
                return $photo->move($destinationPath, $fileName);
            }
            if($this->pathName == Config::get('variable_constants.file_path.leave')) {
                $i = 0;
                foreach($photo as $p) {
                    $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'leaveAppliedFiles';
                    $p->move($destinationPath, $fileName[$i++]);
                }
                return true;
            }
            if($this->pathName == Config::get('variable_constants.file_path.event')) {
                $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'eventFiles';
                return $photo->move($destinationPath, $fileName);
            }
            if($this->pathName == Config::get('variable_constants.file_path.complaint')) {
                $destinationPath = storage_path('app/public') . DIRECTORY_SEPARATOR . 'complaintFiles';
                return $photo->move($destinationPath, $fileName);
            }
        }
    }
