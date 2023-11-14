<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BasicInfo;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Organization;

class UserRepository
{
    private $name, $id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getBranches()
    {
        return Branch::all();
    }

    public function getDepartments($data)
    {
        return DB::table('branch_departments')->where('branch_id', '=', $data->branchId)->get();
    }

    public function getDesignations($data)
    {
        return DB::table('branch_designations')->where('branch_id', '=', $data->branchId)->get();
    }

    public function getDeptDesgName($deptId, $desgId)
    {
        $deptName = array();
        foreach ($deptId as $d) {
            $array = DB::table('departments')->select('name')->where('id', '=', $d)->first();
            array_push($deptName, $array->name);
        }

        $desgName = array();
        foreach ($desgId as $d) {
            $array = DB::table('designations')->select('name')->where('id', '=', $d)->first();
            array_push($desgName, $array->name);
        }

        return [$deptId, $deptName, $desgId, $desgName];
    }

    public function getBranchNameForTable($branchId)
    {
        if($branchId == null)
            return null;
        $branchName = DB::table('branches')->where('id', '=', $branchId)->first('name');
        return $branchName->name;
    }

    public function getDepartmentNameForTable($deptId)
    {
        if($deptId == null)
            return null;
        $deptName = DB::table('departments')->where('id', '=', $deptId)->first('name');
        return $deptName->name;
    }

    public function getDesignationNameForTable($desgId)
    {
        if($desgId == null)
            return null;
        $desgName = DB::table('designations')->where('id', '=', $desgId)->first('name');
        return $desgName->name;
    }

    public function getOrganizations()
    {
        return Organization::all();
    }

    public function getTableData()
    {    
        return DB::table('users as u')
        ->leftJoin('basic_info as bi', function ($join) {
            $join->on('u.id', '=', 'bi.user_id');
        })
        ->where('u.is_super_user', '0')
        ->groupBy('u.id')
        ->select('u.id', 'u.image', 'u.employee_id', 'u.full_name', 'u.email', 'u.phone_number', 'bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.joining_date', 'u.status', 'u.deleted_at')
        ->get();
    }

    public function storeUser($data, $fileName)
    {
        if ($data->career_start_date == null) {
            $data->career_start_date = $data->joining_date;
        }
        $create_user = User::create([
            'employee_id' => $data->employee_id,
            'full_name' => $data->full_name,
            'nick_name' => $data->nick_name,
            'email' => $data->preferred_email,
            'phone_number' => $data->phone,
            'password' => Hash::make("welcome"),
            'image' => $fileName,
            'is_super_user' => 0,
            'is_registration_complete' => 0,
            'is_password_changed' => 0,
            'is_onboarding_complete' => 0,
            'status' => 1
        ]);

        if($create_user && !is_numeric($data->organizationName))
        {
            $create_org = Organization::create([
                'name' => $data->organizationName
            ]);
        }

            if($create_user && $data->organizationName!= null && !is_numeric($data->organizationName)) {
                $create_org = Organization::create([
                    'name' => $data->organizationName
                ]);
                $result1 = BasicInfo::create([
                    'user_id' => $create_user->id,
                    'branch_id' => $data->branchId,
                    'department_id' => $data->departmentId,
                    'designation_id' => $data->designationId,
                    'personal_email' => $data->personal_email,
                    'preferred_email' => $data->preferred_email,
                    'joining_date' => $data->joining_date,
                    'career_start_date' => $data->career_start_date,
                    'last_organization_id' => $create_org->id
                ]);
                DB::commit();
                return $result1;
            } else {
                $result2 = BasicInfo::create([
                    'user_id' => $create_user->id,
                    'branch_id' => $data->branchId,
                    'department_id' => $data->departmentId,
                    'designation_id' => $data->designationId,
                    'personal_email' => $data->personal_email,
                    'preferred_email' => $data->preferred_email,
                    'joining_date' => $data->joining_date,
                    'career_start_date' => $data->career_start_date,
                    'last_organization_id' => $data->organizationName
                ]);
                DB::commit();
                return $result2;
            }
        try {
            DB::beginTransaction();
            $create_user = User::create([
                'employee_id' => $data->employee_id,
                'full_name' => $data->full_name,
                'nick_name' => $data->nick_name,
                'email' => $data->preferred_email,
                'phone_number' => $data->phone,
                'password' => Hash::make("welcome"),
                'image' => $fileName,
                'is_super_user' => 0,
                'is_registration_complete' => 0,
                'is_password_changed' => 0,
                'is_onboarding_complete' => 0,
                'status' => 1
            ]);
            if($create_user && !is_numeric($data->organizationName))
            {
                $create_org = Organization::create([
                    'name' => $data->organizationName
                ]);

                if($create_user && $data->organizationName!= null && !is_numeric($data->organizationName)) {
                    $create_org = Organization::create([
                        'name' => $data->organizationName
                    ]);
                    $result1 = BasicInfo::create([
                        'user_id' => $create_user->id,
                        'branch_id' => $data->branchId,
                        'department_id' => $data->departmentId,
                        'designation_id' => $data->designationId,
                        'personal_email' => $data->personal_email,
                        'preferred_email' => $data->preferred_email,
                        'joining_date' => $data->joining_date,
                        'career_start_date' => $data->career_start_date,
                        'last_organization_id' => $create_org->id
                    ]);
                    DB::commit();
                    return $result1;
                } else {
                    $result2 = BasicInfo::create([
                        'user_id' => $create_user->id,
                        'branch_id' => $data->branchId,
                        'department_id' => $data->departmentId,
                        'designation_id' => $data->designationId,
                        'personal_email' => $data->personal_email,
                        'preferred_email' => $data->preferred_email,
                        'joining_date' => $data->joining_date,
                        'career_start_date' => $data->career_start_date,
                        'last_organization_id' => $data->organizationName
                    ]);
                    DB::commit();
                    return $result2;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
              
    public function editUser($id)
    {
        return DB::table('users as u')
        ->leftJoin('basic_info as bi', function ($join) {
            $join->on('u.id', '=', 'bi.user_id');
        })
        ->leftJoin('organizations as o', function ($join) {
            $join->on('bi.last_organization_id', '=', 'o.id');
        })
        ->where('u.is_super_user', '0')
        ->where('u.id', $id)
        ->select('u.*', 'bi.*', 'o.id', 'o.name')
        ->first();
    }

    public function updateUser($data, $id, $fileName)
    {
        if($data->career_start_date == null)
            $data->career_start_date = $data->joining_date;
        
        try {
            DB::beginTransaction();
        
            $user = User::find($id);
            if($fileName == null)
                $fileName = $user->image;

            DB::table('users')->where('id', $id)->update([
                'full_name' => $data->full_name,
                'nick_name' => $data->nick_name,
                'email' => $data->preferred_email,
                'phone_number' => $data->phone,
                'password' => Hash::make("welcome"),
                'image' => $fileName,
                'is_super_user' => 0,
                'is_registration_complete' => 0,
                'is_password_changed' => 0,
                'is_onboarding_complete' => 0,
                'status' => 1
            ]);
            if($data->organizationName!= null && !is_numeric($data->organizationName)) {
                $create_org = Organization::create([
                    'name' => $data->organizationName
                ]);
                DB::table('basic_info')->where('user_id', $id)->update([
                    'branch_id' => $data->branchId,
                    'department_id' => $data->departmentId,
                    'designation_id' => $data->designationId,
                    'personal_email' => $data->personal_email,
                    'preferred_email' => $data->preferred_email,
                    'joining_date' => $data->joining_date,
                    'career_start_date' => $data->career_start_date,
                    'last_organization_id' => $create_org->id
                ]);
                DB::commit();
                return true;
            } else {
                DB::table('basic_info')->where('user_id', $id)->update([
                    'branch_id' => $data->branchId,
                    'department_id' => $data->departmentId,
                    'designation_id' => $data->designationId,
                    'personal_email' => $data->personal_email,
                    'preferred_email' => $data->preferred_email,
                    'joining_date' => $data->joining_date,
                    'career_start_date' => $data->career_start_date,
                    'last_organization_id' => $data->organizationName
                ]);
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function destroyUser($id)
    {
        $data = User::find($id);
        $data->update(array('status' => 0));
        return $data->delete();
    }

    public function restoreUser($id)
    {
        return DB::table('users')->where('id', $id)->limit(1)->update(array('deleted_at' => NULL));
    }

    public function updateStatus($id)
    {
        $data = User::find($id);
        if($data->status)
            $data->update(array('status' => 0));
        else
            $data->update(array('status' => 1));
    }

    public function getCurrentBranchName($id)
    {
        $data =  DB::table('branches')->where('id', $id)->first('name');
        return $data->name;
    }

    public function getCurrentDepartmentName($id)
    {
        if($id == null)
            return null;
        $data =  DB::table('departments')->where('id', $id)->first('name');
        return $data->name;
    }
    
    public function getCurrentDesignationName($id)
    {
        if($id == null)
            return null;
        $data =  DB::table('designations')->where('id', $id)->first('name');
        return $data->name;
    }

    public function getCurrentOrganizationName($id)
    {
        if($id == null)
            return null;
        $data =  DB::table('organizations')->where('id', $id)->first('name');
        return $data->name;
    }

    public function isEmployeeIdExists($employee_id)
    {
        return DB::table('users')->where('employee_id', '=', $employee_id)->first();
    }

    public function isPersonalEmailExists($personal_email)
    {
        return DB::table('basic_info')->where('personal_email', '=', $personal_email)->first();
    }

    public function isPreferredEmailExists($preferred_email)
    {
        return DB::table('users')->where('email', '=', $preferred_email)->first();
    }

    public function isPhoneExists($phone)
    {
        return DB::table('users')->where('phone_number', '=', $phone)->first();
    }
    public function isPersonalEmailExistsForUpdate($personal_email, $current_personal_email)
    {
        return DB::table('basic_info')->where('personal_email', '!=', $current_personal_email)->where('personal_email', '=', $personal_email)->first();
    }

    public function isPreferredEmailExistsForUpdate($preferred_email, $current_preferred_email)
    {
        return DB::table('users')->where('email', '!=', $current_preferred_email)->where('email', '=', $preferred_email)->first();
    }

    public function isPhoneExistsForUpdate($phone, $current_phone)
    {
        return DB::table('users')->where('phone_number', '!=', $current_phone)->where('phone_number', '=', $phone)->first();
    }

    public function getUserInfo()
    {
        return User::with(['personalInfo', 'academicInfo', 'bankingInfo', 'emergencyContacts', 'basicInfo'])
            ->with('academicInfo.degree')
            ->with('academicInfo.institute')
            ->with('bankingInfo.bank')
            ->with('bankingInfo.nominees')
            ->with('basicInfo.branch')
            ->with('basicInfo.department')
            ->with('basicInfo.designation')
            ->with('basicInfo.lastOrganization')
            ->where('id', $this->id)
            ->first();
    }
}
