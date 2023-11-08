<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Config;

class UserService
{
    private $userRepository, $fileUploadService;

    public function __construct(UserRepository $userRepository, FileUploadService $fileUploadService)
    {
        $this->userRepository = $userRepository;
        $this->fileUploadService = $fileUploadService;
    }

    public function getBranches()
    {
        return $this->userRepository->getBranches();
    }

    public function getDeptDesg($data)
    {
        $departments = $this->userRepository->getDepartments($data);
        $designations = $this->userRepository->getDesignations($data);

        $deptId = array();
        foreach ($departments as $d) {
            array_push($deptId, $d->department_id);
        }

        $desgId = array();
        foreach ($designations as $d) {
            array_push($desgId, $d->designation_id);
        }

        return $this->userRepository->getDeptDesgName($deptId, $desgId);
    }

    public function getOrganizations()
    {
        return $this->userRepository->getOrganizations();
    }

    public function storeUser($data)
    {
        $fileName = null;
        if($data->hasFile('photo')) {
            $fileName = $this->fileUploadService->setPath($data['photo']);
            $this->fileUploadService->uploadFile($fileName, $data['photo']);
            return $this->userRepository->storeUser($data, $fileName);
        }
        return $this->userRepository->storeUser($data, $fileName);
    }

    public function getTableData()
    {
        $result = $this->userRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            // foreach ($result as $key=>$row) {
            //     $id = $row->id;
            //     $name = $row->name;
            //     $total_leaves = $row->total_leaves;
                
            //     $currentYear = date("Y"); 
                
            //     if($year >= $currentYear) {
            //         if ($total_leaves > 0) {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_update\"name=\"btn_update\"data-toggle=\"modal\" data-target=\"#modal-block-slideup\" onclick=\"openmodal($id)\"> 
            //             <i class=\"fas fa-edit text-success mr-1\"></i>Update</td>";
                        
            //         } else {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_add\"name=\"btn_add\"data-toggle=\"modal\"data-target=\"#modal-block-slideup\" onclick=\"openmodal($id)\"> 
            //             <i class=\"fas fa-plus text-success mr-1\"></i>Add</td>";
            //         }
            //     } else {
            //         if ($total_leaves > 0) {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_update\"name=\"btn_update\"data-toggle=\"modal\" data-target=\"#modal-block-slideup\"disabled=\"true\"> 
            //             <i class=\"fas fa-edit text-success mr-1\"></i>Update</td>";
                        
            //         } else {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_add\"name=\"btn_add\"data-toggle=\"modal\"data-target=\"#modal-block-slideup\"disabled=\"true\"> 
            //             <i class=\"fas fa-plus text-success mr-1\"></i>Add</td>";
            //         }
            //     }
                
            //     $temp = array();
            //     array_push($temp, $key+1);
            //     array_push($temp, $name);
            //     array_push($temp, $year);
            //     array_push($temp, $total_leaves);
            //     array_push($temp, $action_btn);
            //     array_push($data, $temp);
            // }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }

    public function validateInputs($data)
    {
        $flag = 1;
        $is_employeeId_exists = $this->userRepository->isEmployeeIdExists($data->employee_id);
        $is_personalEmail_exists = $this->userRepository->isPersonalEmailExists($data->personal_email);
        $is_preferredEmail_exists = $this->userRepository->isPreferredEmailExists($data->preferred_email);
        $is_phone_exists = $this->userRepository->isPhoneExists($data->phone);
        
        if($is_employeeId_exists != null) {
            $flag = 0;
            return [
                'success' => false,
                'error_employee_id' => 'employee id already taken',
            ];
        }
        if($is_personalEmail_exists != null) {
            $flag = 0;
            return [
                'success' => false,
                'error_personal_email' => 'email already taken',
            ];
        }
        if($is_preferredEmail_exists != null) {
            $flag = 0;
            return [
                'success' => false,
                'error_preferred_email' => 'email already taken',
            ];
        }
        if($is_phone_exists != null) {
            $flag = 0;
            return [
                'success' => false,
                'error_phone' => 'number already taken',
            ];
        }
        if($flag == 1) {
            return [
                'success' => true,
            ];
        }
        
    }
}
