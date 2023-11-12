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

    public function editUser($id)
    {
        return $this->userRepository->editUser($id);
    }

    public function updateUser($data, $id)
    {
        $fileName = null;
        if($data->hasFile('photo')) {
            $fileName = $this->fileUploadService->setPath($data['photo']);
            $this->fileUploadService->uploadFile($fileName, $data['photo']);
            return $this->userRepository->updateUser($data, $id, $fileName);
        }
        return $this->userRepository->updateUser($data, $id, $fileName);
    }

    public function destroyUser($id)
    {
        return $this->userRepository->destroyUser($id);
    }

    public function restoreUser($id)
    {
        return $this->userRepository->restoreUser($id);
    }

    public function updateStatus($id)
    {
        return $this->userRepository->updateStatus($id);
    }

    public function getCurrentBranchName($id)
    {
        return $this->userRepository->getCurrentBranchName($id);
    }

    public function getCurrentDepartmentName($id)
    {
        return $this->userRepository->getCurrentDepartmentName($id);
    }

    public function getCurrentDesignationName($id)
    {
        return $this->userRepository->getCurrentDesignationName($id);
    }

    public function getCurrentOrganizationName($id)
    {
        return $this->userRepository->getCurrentOrganizationName($id);
    }

    public function getTableData()
    {
        $result = $this->userRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $imgName = $row->image;
                $employee_id = $row->employee_id;
                $name = $row->full_name;
                $email = $row->email;
                $phone_number = $row->phone_number;
                $branch_name = $this->userRepository->getBranchNameForTable($row->branch_id);
                $department_name = $this->userRepository->getDepartmentNameForTable($row->department_id);
                $designation_name = $this->userRepository->getDesignationNameForTable($row->designation_id);
                $joining_date = $row->joining_date;

                $url = asset('storage/userImg/'. $imgName);
                $img = "<td> <img src=\"$url\" class=\"w-100 rounded\" alt=\"...\"></td>";

                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = route('user.edit', $id);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$id\", \"$status_msg\")'> $status_msg </a>";
                if ($row->deleted_at) {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_restore_modal(\"$id\", \"$name\")'>Restore</a>";
                } else {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\", \"$name\")'>Delete</a>";
                }
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $action_btn .= "$edit_btn $toggle_btn $toggle_delete_btn";
                $action_btn .= "</div>
                                    </div>
                                </div>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $img);
                array_push($temp, $employee_id);
                array_push($temp, $name);
                array_push($temp, $email);
                array_push($temp, $phone_number);
                array_push($temp, $branch_name);
                array_push($temp, $department_name);
                array_push($temp, $designation_name);
                array_push($temp, $joining_date);
                array_push($temp, $status);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $action_btn);
                array_push($data, $temp);
            }


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

    public function updateInputs($data)
    {
        $flag = 1;
        $is_personalEmail_exists_for_update = $this->userRepository->isPersonalEmailExistsForUpdate($data->personal_email, $data->current_personal_email);
        $is_preferredEmail_exists_for_update = $this->userRepository->isPreferredEmailExistsForUpdate($data->preferred_email, $data->current_preferred_email);
        $is_phone_exists_for_update = $this->userRepository->isPhoneExistsForUpdate($data->phone, $data->current_phone);
        
        if($is_personalEmail_exists_for_update != null) {
            $flag = 0;
            return [
                'success' => false,
                'error_personal_email' => 'email already taken',
            ];
        }
        if($is_preferredEmail_exists_for_update != null) {
            $flag = 0;
            return [
                'success' => false,
                'error_preferred_email' => 'email already taken',
            ];
        }
        if($is_phone_exists_for_update != null) {
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
