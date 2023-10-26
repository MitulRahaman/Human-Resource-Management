<?php

namespace App\Services;

use App\Repositories\LeaveRepository;
use Illuminate\Support\Facades\Config;

class LeaveService
{
    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function indexLeave()
    {
        return $this->leaveRepository->indexLeave();
    }

    public function storeLeave($data)
    {
        return $this->leaveRepository->storeLeave($data);
    }

    public function editLeave($id)
    {
        return $this->leaveRepository->editLeave($id);
    }

    public function updateLeave($data, $id)
    {
        return $this->leaveRepository->updateLeave($data, $id);
    }

    public function updateStatus($id)
    {
        return $this->leaveRepository->updateStatus($id);
    }

    public function destroyLeave($id)
    {
        return $this->leaveRepository->destroyLeave($id);
    }

    public function restoreLeave($id)
    {
        return $this->leaveRepository->restoreLeave($id);
    }

    public function validateInputs($data)
    {
        $this->leaveRepository->setName($data['name']);
        $is_name_exists = $this->leaveRepository->isNameExists();
        if ($data->name == null) {
            return [
                'success' => false,
                'name_null_msg' => 'Please select a name',
            ];
        } else if($is_name_exists) {
            return [
                'success' => false,
                'name_msg' => 'Name already taken',
            ];
        }
        else {
            return [
                'success' => true,
                'name_msg' => null,
            ];
        }
    }

    public function UpdateInputs($data)
    {
        $this->leaveRepository->updateName($data['name']);
        $is_name_exists_for_update = $this->leaveRepository->isNameExistsForUpdate($data['current_name']);
        
        if ($data->name == null) {
            return [
                'success' => false,
                'name_null_msg' => 'Please select a name',
            ];
        } else if(!$is_name_exists_for_update) {
                return [
                    'success' => false,
                    'name_msg' => 'Name already taken',
                ];
        }
        else {
            return [
                'success' => true,
                'name_msg' => null,
            ];
        }
    }
 }
