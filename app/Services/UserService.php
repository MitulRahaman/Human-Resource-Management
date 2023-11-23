<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
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

    public function getRoles()
    {
        return $this->userRepository->getRoles();
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
        $formattedPhone = sprintf('%011d', trim(preg_replace('/[\W]+/', '', $data->phone), '-'));
        $fileName = null;
        if($data['photo']) {
            $fileName = $this->fileUploadService->setPath($data['photo']);
            $this->fileUploadService->uploadFile($fileName, $data['photo']);
            return $this->userRepository->storeUser($data, $fileName, $formattedPhone);
        }
        return $this->userRepository->setFile($fileName)->storeUser($data, $formattedPhone);
    }

    public function editUser($id)
    {
        $user = $this->userRepository->setId($id)->getUserInfo();
        $user->phone_number = ltrim($user->phone_number, "0");
        return $user;
    }

    public function updateUser($data, $id)
    {
        $formattedPhone = sprintf('%011d', trim(preg_replace('/[\W]+/', '', $data->phone), '-'));
        $fileName = null;
        if($data->hasFile('photo')) {
            $fileName = $this->fileUploadService->setPath($data['photo']);
            $this->fileUploadService->uploadFile($fileName, $data['photo']);
            return $this->userRepository->updateUser($data, $id, $fileName);
        }
        return $this->userRepository->setId($id)->setFile($fileName)->updateUser($data, $formattedPhone);
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
                $branch_name = $this->userRepository->setBranchId($row->branch_id)->getBranchName();
                $department_name = $this->userRepository->setDepartmentId($row->department_id)->getDepartmentName();
                $designation_name = $this->userRepository->setDesignationId($row->designation_id)->getDesignationName();
                $role_name = $this->userRepository->setRoleId($row->role_id)->getRoleName();
                $joining_date = date("d-m-Y", strtotime($row->joining_date));

                if($imgName) {
                    $url = asset('storage/userImg/'. $imgName);
                    $img = "<td> <img src=\"$url\" class=\"w-100 rounded\" alt=\"user_img\"></td>";
                } else {
                    $img = "<td> <img src=\"https://www.pikpng.com/pngl/b/292-2924795_user-icon-png-transparent-white-user-icon-png.png\" class=\"w-100 rounded\" alt=\"user_img\"></td>";
                }
                


                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = url('user/'.$id.'/edit');
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $profile_edit_url = url('user/profile/'.$id.'/edit');
                $profile_edit_btn= "<a class=\"dropdown-item\" href=\"$profile_edit_url\">Edit Full Profile</a>";
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

                $action_btn .= "$edit_btn ";
                if($row->id==Auth::id())
                    $action_btn .="$profile_edit_btn ";
                elseif ($this->userRepository->isSuperUser(Auth::id()))
                    $action_btn .="$profile_edit_btn ";
                $action_btn .=" $toggle_btn $toggle_delete_btn";
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
                array_push($temp, $role_name);
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

    public function getUserInfo($id=null)
    {
        if (!$id) {
            $this->userRepository->setId(Auth::id());
        } else {
            $this->userRepository->setId($id);
        }
        return $this->userRepository->getUserInfo();
    }
    public function getInstitutes()
    {
        return $this->userRepository->getInstitutes();
    }
    public function getDegree()
    {
        return $this->userRepository->getDegree();
    }
    public function getBank()
    {
        return $this->userRepository->getBank();
    }
    public function updateProfile($data)
    {
        try {
            $file_name=null;
            if (isset($data['nominee_photo'])) {

                $extension = $data['nominee_photo']->getClientOriginalExtension();
                $file_name = random_int(0001, 9999).'.'.$extension;
                $file_path = 'nominee/'.$file_name;
                Storage::disk('public')->put($file_path, file_get_contents($data['nominee_photo']));
            } else {
                $file_path = null;
            }
         $this->userRepository->setId($data['id'])
            ->setFatherName($data['father_name'])
            ->setMotherName($data['mother_name'])
            ->setNID($data['nid'])
            ->setBirthCertificate($data['birth_certificate']? $data['birth_certificate']:'')
            ->setPassportNo($data['passport_no']? $data['passport_no']:'')
            ->setGender($data['gender'])
            ->setReligion($data['religion'])
            ->setBloodGroup($data['blood_group'])
            ->setDob($data['dob'])
            ->setMeritalStatus($data['marital_status'])
            ->setNoOfChildren($data['no_of_children']? $data['no_of_children']:'')
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->setPresentAddress($data['present_address'])
            ->setPermanentAddress($data['permanent_address'])
            ->setEmergencyContactName($data['emergency_contact_name'])
            ->setEmergencyContactRelation($data['relation'])
            ->setEmergencyContact($data['emergency_contact'])
            ->setEmergencyContactName2($data['emergency_contact_name2'])
            ->setEmergencyContactRelation2($data['relation2'])
            ->setEmergencyContact2($data['emergency_contact2'])
            ->setInstituteId($data['institute_id'])
            ->setDegreeId($data['degree_id'])
            ->setMajor($data['major'])
            ->setGPA($data['gpa'])
            ->setPassingYear($data['year'])
            ->setBankId($data['bank_id'])
            ->setAccountName($data['account_name'])
            ->setAccountNumber($data['account_number'])
            ->setBranch($data['branch'])
            ->setRoutingNumber($data['routing_number']? $data['routing_number']:'')
            ->setNomineeName($data['nominee_name'])
            ->setNomineeNID($data['nominee_nid'])
            ->setNomineePhoto($file_name)
            ->setNomineeRelation($data['nominee_relation'])
            ->setNomineePhoneNumber($data['nominee_phone_number']? $data['nominee_phone_number']:'')
            ->setNomineeEmail($data['nominee_email']? $data['nominee_email']:'');

            return  $this->userRepository->saveAllProfileInfo();

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

    }
    public function getEmergencyContacts($id)
    {
        return $this->userRepository->getEmergencyContacts($id);
    }
    public function getBankInfo($id)
    {
        return $this->userRepository->getBankInfo($id);
    }
    public function deleteAcademicInfo($id)
    {
        return $this->userRepository->deleteAcademicInfo($id);
    }
    public function getUserAddress($id)
    {
        return $this->userRepository->getUserAddress($id);
    }
    public function getOfficialInfo($id)
    {
        return $this->userRepository->getOfficialInfo($id);
    }
    public function getInstituteDegree($academy)
    {
        return $this->userRepository->getInstituteDegree($academy);
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
    public function getAllUsers($id=null)
    {
        return $this->userRepository->getAllUsers($id);
    }
    public function getLineManagers($id)
    {
        return $this->userRepository->getLineManagers($id);
    }
}
