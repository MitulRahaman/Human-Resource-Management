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

    public function getAllEvents()
    {
        return $this->eventRepository->getAllEvents();
    }

    // public function editLeave($id)
    // {
    //     return $this->leaveApplyRepository->setId($id)->getLeaveInfo();
    // }

    // public function updateLeave($data, $id)
    // {
    //     return $this->leaveApplyRepository->setId($id)->updateLeave($data);
    // }

    // public function delete($id)
    // {
    //     return $this->leaveApplyRepository->delete($id);
    // }

}
