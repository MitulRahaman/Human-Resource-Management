<?php

namespace App\Repositories;

use App\Models\AcademicInfo;
use App\Models\Bank;
use App\Models\BankingInfo;
use App\Models\Degree;
use App\Models\EmergencyContact;
use App\Models\Institute;
use App\Models\Nominee;
use App\Models\PersonalInfo;
use Carbon\Carbon;
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
        return DB::table('users as u')
        ->leftJoin('basic_info as bi', function ($join) {
            $join->on('u.id', '=', 'bi.user_id');
        })
        ->whereNull('u.deleted_at')
        ->groupBy('u.id')
        ->select('u.id', 'u.image', 'u.employee_id', 'u.full_name', 'u.email', 'u.phone_number', 'bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.joining_date')
        ->get();
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
    public function getInstitutes()
    {
        return Institute::where('status',1)->get();
    }
    public function getDegree()
    {
        return Degree::get();
    }
    public function getBank()
    {
        return Bank::get();
    }
    public function getEmergencyContacts()
    {
        return EmergencyContact::get();
    }
    public function getBankInfo($id)
    {
        return DB::table('banking_info as b')
            ->where('b.user_id',$id)
            ->join('nominees as n', 'b.id', '=', 'n.banking_info_id')
            ->join('banks', 'banks.id', '=', 'b.bank_id')
            ->select('b.*', 'n.*', 'banks.name as bank_name', 'banks.address as bank_address')
            ->first();
    }
    public function updateProfile($data)
    {
        $d = date('Y-m-d H:i:s');
//        dd($d);
        DB::transaction(function () use ($data,$d){
            try{
                $personal_info = PersonalInfo::where('user_id',$data['id'])->first();
                if(!$personal_info)
                {
                    $personal_info = new PersonalInfo();
                    $personal_info->user_id = $data['id'];
                    $personal_info->created_at = $d;
                }
                else
                {
                    $personal_info->updated_at = $d;
                }
                $personal_info->father_name = $data['father_name'];
                $personal_info->mother_name	 = $data['mother_name']	;
                $personal_info->nid = $data['nid'];
                $personal_info->birth_certificate = ($data['birth_certificate'])?  $data['birth_certificate']:'';
                $personal_info->passport_no = $data['passport_no']? $data['passport_no']:'';
                $personal_info->gender = $data['gender'];
                $personal_info->religion = $data['religion'];
                $personal_info->blood_group = $data['blood_group'];
                $personal_info->dob = $data['dob'];
                $personal_info->marital_status = $data['marital_status'];
                $personal_info->no_of_children = $data['no_of_children']? $data['no_of_children']:'';
                $personal_info->save();

                $emergency_contact = new EmergencyContact();
                $emergency_contact->user_id = $data['id'];
                $emergency_contact->name  =$data['emergency_contact_name'];
                $emergency_contact->relation = $data['relation'];
                $emergency_contact->phone_number = $data['emergency_contact'];
                $emergency_contact->created_at = $d;
                $emergency_contact->save();

                $academic_info = AcademicInfo::where('user_id',$data['id'])->first();
                if(!$academic_info)
                {
                    $academic_info = new AcademicInfo();
                    $academic_info->user_id = $data['id'];
                    $academic_info->created_at = $d;
                }
                else
                {
                    $academic_info->updated_at = $d;
                }
                $academic_info->institute_id = $data['institute_id'];
                $academic_info->degree_id = $data['degree_id'];
                $academic_info->major = $data['major'];
                $academic_info->passing_year = $data['year'];
                $academic_info->save();

                $bank_info = BankingInfo::where('user_id',$data['id'])->first();
                if(!$bank_info)
                {
                    $bank_info = new BankingInfo();
                    $bank_info->user_id = $data['id'];
                    $bank_info->created_at = $d;
                }
                else
                {
                    $bank_info->updated_at = $d;
                }
                $bank_info->bank_id =$data['bank_id'];
                $bank_info->account_name =$data['account_name'];
                $bank_info->account_number =$data['account_number'];
                $bank_info->branch =$data['branch'];
                $bank_info->routing_no =$data['routing_number']? $data['routing_number']:'';
                $bank_info->save();

                $nominee = Nominee::where('banking_info_id',$bank_info->id)->first();
                if(!$nominee)
                {
                    $nominee = new Nominee();
                    $nominee->banking_info_id = $bank_info->id;
                    $nominee->created_at = $d;
                }
                else
                {
                    $nominee->updated_at = $d;
                }
                $nominee->name = $data['nominee_name'];
                $nominee->nid = $data['nominee_nid'];
                $nominee->photo = $data['nominee_photo'];
                $nominee->relation = $data['nominee_relation'];
                $nominee->phone_number = $data['nominee_phone_number']? $data['nominee_phone_number']:'';
                $nominee->email = $data['nominee_email']? $data['nominee_email']:'';
                $nominee->save();
            } catch (\Exception $e) {
                DB::rollBack();
                return $e;
            }
           return "success";
        });
        return "success";
    }
}
