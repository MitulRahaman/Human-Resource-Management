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
    private $name;

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

    public function getOrganizations()
    {
        return Organization::all();
    }

    public function storeUser($data, $fileName)
    {
        if($data->career_start_date == null)
            $data->career_start_date = $data->joining_date;
        
        $create_user = User::create([
            'employee_id' => $data->employee_id,
            'full_name' => $data->full_name,
            'nick_name' => $data->nick_name,
            'email' => $data->personal_email,
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

            return BasicInfo::create([
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
        } else
        {
            return BasicInfo::create([
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
        }
    }

    public function getTableData()
    {
        return DB::table('basic_info')->get();
    }

    public function isEmployeeIdExists($employee_id)
    {
        return DB::table('users')->where('employee_id', '=', $employee_id)->first();
    }

    public function isPersonalEmailExists($personal_email)
    {
        return DB::table('users')->where('email', '=', $personal_email)->first();
    }

    public function isPreferredEmailExists($preferred_email)
    {
        return DB::table('basic_info')->where('preferred_email', '!=', null)->where('preferred_email', '=', $preferred_email)->first();
    }

    public function isPhoneExists($phone)
    {
        return DB::table('users')->where('phone_number', '=', $phone)->first();
    }

}