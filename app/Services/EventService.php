<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Repositories\EventRepository;
use App\Traits\AuthorizationTrait;

class EventService
{
    use AuthorizationTrait;
    private $eventRepository, $fileUploadService;

    public function __construct(EventRepository $eventRepository, FileUploadService $fileUploadService)
    {
        $this->eventRepository = $eventRepository;
        $this->fileUploadService = $fileUploadService;
    }

    public function getDeptPart($data)
    {
        if(!$data->departmentId) {
            $deptId = array();
            $departments = $this->eventRepository->setBranchId($data->branchId)->getDepartments();
            foreach ($departments as $d) {
                array_push($deptId, $d->department_id);
            }
            return $this->eventRepository->setDepartmentId($deptId)->getDepartmentName();
        }
        else {
            $partId = array();
            foreach($data->departmentId as $deptId) {
                $participants = $this->eventRepository->setDepartmentId($deptId)->getParticipants();
                foreach ($participants as $p) {
                    array_push($partId, $p->user_id);
                }
            }
            return $this->eventRepository->setParticipantId($partId)->getParticipantName();
        }
    }

    public function storeEvents($request)
    {
        $fileName = null;
        if($request['photo']) {
            $fileName = $this->fileUploadService->setPath($request['photo']);
            $this->fileUploadService->setPathName(Config::get('variable_constants.file_path.event'))->uploadFile($fileName, $request['photo']);
        }

        return $this->eventRepository
            ->setTitle($request->title)
            ->setBranchId($request->branchId)
            ->setDepartmentId($request->departmentId)
            ->setParticipantId($request->participantId)
            ->setStartDate($request->startDate)
            ->setEndDate($request->endDate)
            ->setDescription($request->description)
            ->setFile($fileName)
            ->storeEvents();
    }

    public function getAllEvents($id = null)
    {
        $data = $this->eventRepository->setEventId($id)->getAllEvents();
        if(!$data->isEmpty()) {
            foreach($data as $d) {
                $events[] = [
                    'id' => $d->id,
                    'title' => $d->title,
                    'start' => $d->start_date,
                    'end' => $d->end_date,
                    'description' => $d->description,
                    'itinerary' => $d->itinerary,
                ];
            }
            return $events;
        } else {
            $events[] = null;
        }

    }

    public function getCurrentBranch($id)
    {
        return $this->eventRepository->setEventId($id)->getCurrentBranch();
    }

    public function getCurrentDepartments($id)
    {
        return $this->eventRepository->setEventId($id)->getCurrentDepartments();
    }

    public function getAvailableDepartments($id, $currentDepartments)
    {
        $allDept = $this->eventRepository->setEventId($id)->getAvailableDepartments();
        $temp = array();
        $temp = $allDept;
        foreach($allDept as $key=>$allDept) {
            foreach($currentDepartments as $currentDept) {
                if($allDept->department_id == $currentDept->department_id){

                    $temp->forget($key);
                }
            }
        }
        return $temp;
    }

    public function getCurrentUsers($id)
    {
        return $this->eventRepository->setEventId($id)->getCurrentUsers();
    }

    public function getAvailableUsers($id, $currentDepartments, $currentUsers)
    {
        $allUser = $this->eventRepository->setEventId($id)->getAvailableUsers($currentDepartments);
        $temp = array();
        $temp = $allUser;
        foreach($allUser as $key=>$allUser) {
            foreach($currentUsers as $currentUser) {
                if($allUser->participant_id == $currentUser->participant_id){
                    $temp->forget($key);
                }
            }
        }
        return $temp;
    }

    public function updateEvent($request, $id)
    {
        $fileName = null;
        if($request['photo']) {
            $fileName = $this->fileUploadService->setPath($request['photo']);
            $this->fileUploadService->setPathName(Config::get('variable_constants.file_path.event'))->uploadFile($fileName, $request['photo']);
        }

        return $this->eventRepository
            ->setEventId($id)
            ->setTitle($request->title)
            ->setBranchId($request->branchId)
            ->setDepartmentId($request->departmentId)
            ->setParticipantId($request->participantId)
            ->setStartDate($request->startDate)
            ->setEndDate($request->endDate)
            ->setDescription($request->description)
            ->setFile($fileName)
            ->updateEvents();
    }

    public function destroyEvent($id)
    {
        return $this->eventRepository->setEventId($id)->destroyEvent();
    }

}
