<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;

class DepartmentService
{
    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function indexDepartment()
    {
        return $this->departmentRepository->indexDepartment();
    }

    public function storeDepartment($data)
    {
        return $this->departmentRepository->storeDepartment($data);
    }

    public function departmentInfo($id)
    {
        return $this->departmentRepository->departmentInfo($id);
    }

    public function updateDepartment($data, $id)
    {
        return $this->departmentRepository->updateDepartment($data, $id);
    }

    public function updateStatus($id)
    {
        return $this->departmentRepository->updateStatus($id);
    }

    public function destroyDepartment($id)
    {
        return $this->departmentRepository->destroyDepartment($id);
    }

    public function restoreDepartment($id)
    {
        return $this->departmentRepository->restoreDepartment($id);
    }

    public function validateInputs($data)
    {
        $flag = 1;
        $this->departmentRepository->setName($data['name']);
        $is_name_exists = $this->departmentRepository->isNameExists();
        if ($data->name == null) {
            $flag = 0;
            return [
                'success' => false,
                'name_null_msg' => 'Please select a name',
            ];
        } else {
            if($is_name_exists != null){
                $flag = 0;
                return [
                    'success' => false,
                    'name_msg' => 'Name already taken',
                ];
            }
        }
        if($data->branch == null){
            $flag = 0;
            return [
                'success' => false,
                'branch_null_msg' => 'Please select a branch',
            ];
        }
        if($flag == 1) {
            return [
                'success' => true,
                'name_msg' => null,
            ];
        }
    }

    public function UpdateInputs($data)
    {
        $this->departmentRepository->setName($data['name']);
        $is_name_exists_for_update = $this->departmentRepository->isNameExistsForUpdate($data['current_name']);

        if ($data->name == null) {
            return [
                'success' => false,
                'name_null_msg' => 'Please select a name',
            ];
        } else if($is_name_exists_for_update != null) {
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

    public function getDepartments()
    {
        return $this->departmentRepository->getDepartments();
    }
 }
