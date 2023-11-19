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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BasicInfo;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Organization;
use function Symfony\Component\Finder\size;
use Illuminate\Support\Facades\Storage;

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
        return Branch::where('status', Config::get('variable_constants.activation.active'))->get();
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
        return DB::table('branches')->where('id', '=', $branchId)->first()->name;
    }

    public function getDepartmentNameForTable($deptId)
    {
        if($deptId == null)
            return null;
        return DB::table('departments')->where('id', '=', $deptId)->first()->name;
    }

    public function getDesignationNameForTable($desgId)
    {
        if($desgId == null)
            return null;
        return DB::table('designations')->where('id', '=', $desgId)->first()->name;
    }

    public function getOrganizations()
    {
        return Organization::get();
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
        DB::beginTransaction();
            $formattedJoiningDate = date("Y-m-d", strtotime($data->joining_date)); 
            if ($data->career_start_date == null) {
                $formattedCareerStartDate = $formattedJoiningDate;
            } else {
                $formattedCareerStartDate = date("Y-m-d", strtotime($data->career_start_date));
            }

        try {
            $organization_id = null;
            if ($data['organization_id']) {
                $organization_id = $data['organization_id'];
            }
            if ($data['organization_name']) {
                $organization = new Organization();
                $organization->name = $data['organization_name'];
                $organization->created_at = date('Y-m-d');
                $organization->save();
                $organization_id = $organization->id;
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
            BasicInfo::create([
                'user_id' => $create_user->id,
                'branch_id' => $data->branchId,
                'department_id' => $data->departmentId,
                'designation_id' => $data->designationId,
                'personal_email' => $data->personal_email,
                'preferred_email' => $data->preferred_email,
                'joining_date' => $formattedJoiningDate,
                'career_start_date' => $formattedCareerStartDate,
                'last_organization_id' => $organization_id
            ]);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
              
    public function editUser($id)
    {
        return DB::table('users as u')
            ->leftJoin('basic_info as bi', function ($join) {
                $join->on('u.id', '=', 'bi.user_id');
            })
            ->whereNull('u.deleted_at')
            ->where('u.is_super_user', '=', Config::get('variable_constants.check.no'))
            ->groupBy('u.id')
            ->select('u.id', 'u.image', 'u.status', 'u.deleted_at', 'u.employee_id', 'u.full_name', 'u.email', 'u.phone_number', 'bi.branch_id', 'bi.department_id', 'bi.designation_id', 'bi.joining_date')
            ->get();
    }

    public function updateUser($data, $id, $fileName)
    {
        $formattedJoiningDate = date("Y-m-d", strtotime($data->joining_date)); 
        if ($data->career_start_date == null) {
            $formattedCareerStartDate = $formattedJoiningDate;
        } else {
            $formattedCareerStartDate = date("Y-m-d", strtotime($data->career_start_date));
        }
        
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
    public function deleteAcademicInfo($id)
    {
        $academicInfo= AcademicInfo::findOrFail($id);
        return $academicInfo->delete();
    }
    public function updateProfile($data)
    {
        $date = date('Y-m-d H:i:s');
//        dd($data['institute_id']);
        DB::beginTransaction();
        try {
                $personal_info = PersonalInfo::where('user_id',$data['id'])->first();
                if(!$personal_info)
                {
                    $personal_info = new PersonalInfo();
                    $personal_info->user_id = $data['id'];
                    $personal_info->created_at = $date;
                }
                else
                {
                    $personal_info->updated_at = $date;
                }
                $personal_info->father_name = $data['father_name'];
                $personal_info->mother_name	 = $data['mother_name']	;
                $personal_info->nid = $data['nid'];
                $personal_info->birth_certificate = ($data['birth_certificate'])?  $data['birth_certificate']:'';
                $personal_info->passport_no = $data['passport_no']? $data['passport_no']:'';
                $personal_info->gender = $data['gender'];
                $personal_info->religion = $data['religion'];
                $personal_info->blood_group = $data['blood_group'];
                $personal_info->dob = Carbon::createFromFormat('d-m-Y', $data['dob'])->format('Y-m-d');
                $personal_info->marital_status = $data['marital_status'];
                $personal_info->no_of_children = $data['no_of_children']? $data['no_of_children']:'';
                $personal_info->save();

                $emergency_contact = EmergencyContact::where('user_id',$data['id'])->first();
                if(!$emergency_contact)
                {
                    $emergency_contact = new EmergencyContact();
                    $emergency_contact->user_id = $data['id'];
                    $emergency_contact->created_at = $date;
                }
                else
                {
                    $emergency_contact->updated_at = $date;
                }
                $emergency_contact->name  =$data['emergency_contact_name'];
                $emergency_contact->relation = $data['relation'];
                $emergency_contact->phone_number = $data['emergency_contact'];
                $emergency_contact->save();

                $emergency_contact = EmergencyContact::where('user_id',$data['id'])->skip(1)->first();
                if(!$emergency_contact)
                {
                    $emergency_contact = new EmergencyContact();
                    $emergency_contact->user_id = $data['id'];
                    $emergency_contact->created_at = $date;
                }
                else
                {
                    $emergency_contact->updated_at = $date;
                }
                $emergency_contact->name  =$data['emergency_contact_name2'];
                $emergency_contact->relation = $data['relation2'];
                $emergency_contact->phone_number = $data['emergency_contact2'];
                $emergency_contact->save();

                for($i=0; $i<sizeof($data['institute_id']); $i=$i+1)
                {
                    $academic_info = AcademicInfo::where('user_id',$data['id'])->where('degree_id',$data['degree_id'][$i])->first();
                    if(!$academic_info)
                    {
                        $academic_info = new AcademicInfo();
                        $academic_info->user_id = $data['id'];
                        $academic_info->created_at = $date;
                    }
                    else
                    {
                        $academic_info->updated_at = $date;
                    }
                    $academic_info->institute_id = $data['institute_id'][$i];
                    $academic_info->degree_id = $data['degree_id'][$i];
                    $academic_info->major = $data['major'][$i];
                    $academic_info->gpa = $data['gpa'][$i];
                    $academic_info->passing_year = $data['year'][$i];
                    $academic_info->save();
                }

                $bank_info = BankingInfo::where('user_id',$data['id'])->first();
                if(!$bank_info)
                {
                    $bank_info = new BankingInfo();
                    $bank_info->user_id = $data['id'];
                    $bank_info->created_at = $date;
                }
                else
                {
                    $bank_info->updated_at = $date;
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
                    $nominee->created_at = $date;
                }
                else
                {
                    $nominee->updated_at = $date;
                }
                $file_name=null;
                if ($data['nominee_photo']) {

                    $extension = $data['nominee_photo']->getClientOriginalExtension();
                    $file_name = random_int(0001, 9999).'.'.$extension;
                    $file_path = 'nominee/'.$file_name;
                    Storage::disk('public')->put($file_path, file_get_contents($data['nominee_photo']));
                } else {
                    $file_path = null;
                }
                $nominee->name = $data['nominee_name'];
                $nominee->nid = $data['nominee_nid'];
                $nominee->photo = $file_name;
                $nominee->relation = $data['nominee_relation'];
                $nominee->phone_number = $data['nominee_phone_number']? $data['nominee_phone_number']:'';
                $nominee->email = $data['nominee_email']? $data['nominee_email']:'';
                $nominee->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
