<?php

namespace App\Repositories;

use App\Models\AcademicInfo;
use App\Models\Bank;
use App\Models\BankingInfo;
use App\Models\Degree;
use App\Models\EmergencyContact;
use App\Models\Institute;
use App\Models\LineManager;
use App\Models\Nominee;
use App\Models\PersonalInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BasicInfo;
use App\Models\Branch;
use App\Models\Role;
use App\Models\Department;
use App\Models\Organization;
use function Symfony\Component\Finder\size;
use Illuminate\Support\Facades\Storage;
use App\Models\UserAddress;

class UserRepository
{
    private $name, $id, $father_name, $mother_name,$permanent_address, $present_address, $nid,$dob, $created_at, $updated_at, $birth_certificate,
        $passport_no, $gender, $religion, $blood_group, $marital_status, $no_of_children,$emergency_contact,$relation, $emergency_contact_name,
        $emergency_contact2,$relation2, $emergency_contact_name2, $institute_id ,$degree_id, $major, $gpa, $year, $bank_id, $account_name,
        $account_number, $branch, $routing_number, $nominee_email, $nominee_phone_number, $nominee_relation, $nominee_nid, $nominee_name;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;
        return $this;
    }
    public function setDepartmentId($departmentId)
    {
        $this->departmentId = $departmentId;
        return $this;
    }
    public function setDesignationId($designationId)
    {
        $this->designationId = $designationId;
        return $this;
    }
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
    public function setFatherName($father_name){
        $this->father_name = $father_name;
        return $this;
    }
    public function setNomineeName($nominee_name){
        $this->nominee_name = $nominee_name;
        return $this;
    }
    public function setNomineeNID($nominee_nid){
        $this->nominee_nid = $nominee_nid;
        return $this;
    }
    public function setNomineePhoto($file_name){
        $this->file_name = $file_name;
        return $this;
    }
    public function setNomineeRelation($nominee_relation){
    $this->nominee_relation = $nominee_relation;
    return $this;
    }
    public function setNomineePhoneNumber($nominee_phone_number){
        $this->nominee_phone_number = $nominee_phone_number;
        return $this;
    }
    public function setNomineeEmail($nominee_email){
        $this->nominee_email = $nominee_email;
        return $this;
    }
    public function setBankId($bank_id){
        $this->bank_id = $bank_id;
        return $this;
    }
    public function setAccountName($account_name){
        $this->account_name = $account_name;
        return $this;
    }
    public function setAccountNumber($account_number){
        $this->account_number = $account_number;
        return $this;
    }
    public function setBranch($branch){
        $this->branch = $branch;
        return $this;
    }
    public function setRoutingNumber($routing_number){
        $this->routing_number = $routing_number;
        return $this;
    }
    public function setInstituteId($institute_id){
        $this->institute_id = $institute_id;
        return $this;
    }
    public function setDegreeId($degree_id){
        $this->degree_id = $degree_id;
        return $this;
    }
    public function setMajor($major){
        $this->major = $major;
        return $this;
    }
    public function setGPA($gpa){
        $this->gpa = $gpa;
        return $this;
    }
    public function setPassingYear($year){
        $this->year = $year;
        return $this;
    }

    public function setEmergencyContactName($emergency_contact_name){
        $this->emergency_contact_name = $emergency_contact_name;
        return $this;
    }
    public function setEmergencyContactRelation($relation){
        $this->relation = $relation;
        return $this;
    }
    public function setEmergencyContact($emergency_contact){
        $this->emergency_contact = $emergency_contact;
        return $this;
    }
    public function setEmergencyContactName2($emergency_contact_name2){
        $this->emergency_contact_name2 = $emergency_contact_name2;
        return $this;
    }
    public function setEmergencyContactRelation2($relation2){
        $this->relation2 = $relation2;
        return $this;
    }
    public function setEmergencyContact2($emergency_contact2){
        $this->emergency_contact2 = $emergency_contact2;
        return $this;
    }
    public function setPresentAddress($present_address){
        $this->present_address = $present_address;
        return $this;
    }
    public function setPermanentAddress($permanent_address){
        $this->permanent_address = $permanent_address;
        return $this;
    }
    public function setMotherName($mother_name){
        $this->mother_name = $mother_name;
        return $this;
    }
    public function setNID($nid){
        $this->nid = $nid;
        return $this;
    }
    public function setBirthCertificate($birth_certificate){
        $this->birth_certificate = $birth_certificate;
        return $this;
    }
    public function setPassportNo($passport_no){
        $this->passport_no = $passport_no;
        return $this;
    }
    public function setGender($gender){
        $this->gender = $gender;
        return $this;
    }
    public function setReligion($religion){
        $this->religion = $religion;
        return $this;
    }
    public function setBloodGroup($blood_group){
        $this->blood_group = $blood_group;
        return $this;
    }
    public function setDob($dob){
        $this->dob = $dob;
        return $this;
    }
    public function setMeritalStatus($marital_status){
        $this->marital_status = $marital_status;
        return $this;
    }
    public function setNoOfChildren($no_of_children){
        $this->no_of_children = $no_of_children;
        return $this;
    }
    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
        return $this;
    }
    public function setUpdatedAt($updated_at){
        $this->updated_at = $updated_at;
        return $this;
    }


    public function getBranches()
    {
        return Branch::where('status', Config::get('variable_constants.activation.active'))->get();
    }

    public function getRoles()
    {
        return Role::where('status', Config::get('variable_constants.activation.active'))->get();
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

    public function getBranchName()
    {
        if($this->branchId == null)
            return null;
        return DB::table('branches')->where('id', '=', $this->branchId)->first()->name;
    }

    public function getDepartmentName()
    {
        if($this->departmentId == null)
            return null;
        return DB::table('departments')->where('id', '=', $this->departmentId)->first()->name;
    }

    public function getDesignationName()
    {
        if($this->designationId == null)
            return null;
        return DB::table('designations')->where('id', '=', $this->designationId)->first()->name;
    }

    public function getRoleName()
    {
        if($this->roleId == null)
            return null;
        return DB::table('roles')->where('id', '=', $this->roleId)->first()->name;
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
        ->select('u.id', 'u.image', 'u.employee_id', 'u.full_name', 'u.email', 'u.phone_number', 'bi.branch_id', 'bi.department_id', 'bi.role_id', 'bi.designation_id', 'bi.joining_date', 'u.status', 'u.deleted_at')
        ->get();
    }

    public function storeUser($data, $formattedPhone)
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
                'phone_number' => $formattedPhone,
                'password' => Hash::make("welcome"),
                'image' => $this->file,
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
                'role_id' => $data->roleId,
                'personal_email' => $data->personal_email,
                'preferred_email' => $data->preferred_email,
                'joining_date' => $formattedJoiningDate,
                'career_start_date' => $formattedCareerStartDate,
                'last_organization_id' => $organization_id
            ]);
            foreach ($data['line_manager'] as $line_manager)
            {
                    LineManager::create([
                        'user_id'=>$create_user->id,
                        'line_manager_user_id' => $line_manager,
                    ]);
            }
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

    public function updateUser($data, $formattedPhone)
    {
        $formattedJoiningDate = date("Y-m-d", strtotime($data->joining_date)); 
        if ($data->career_start_date == null) {
            $formattedCareerStartDate = $formattedJoiningDate;
        } else {
            $formattedCareerStartDate = date("Y-m-d", strtotime($data->career_start_date));
        }
        try {
            DB::beginTransaction();
        
            $user = User::find($this->id);
            if($this->file == null)
                $this->file = $user->image;

            DB::table('users')->where('id', $this->id)->update([
                'full_name' => $data->full_name,
                'nick_name' => $data->nick_name,
                'email' => $data->preferred_email,
                'phone_number' => $formattedPhone,
                'password' => Hash::make("welcome"),
                'image' => $this->file,
                'is_super_user' => 0,
                'is_registration_complete' => 0,
                'is_password_changed' => 0,
                'is_onboarding_complete' => 0,
                'status' => 1
            ]);
            LineManager::where('user_id',$id)->delete();
            foreach ($data['line_manager'] as $line_manager)
            {
                LineManager::create([
                    'user_id'=>$id,
                    'line_manager_user_id' => $line_manager,
                ]);
            }
            if($data->organizationName!= null && !is_numeric($data->organizationName)) {
                $create_org = Organization::create([
                    'name' => $data->organizationName
                ]);
                DB::table('basic_info')->where('user_id', $this->id)->update([
                    'branch_id' => $data->branchId,
                    'department_id' => $data->departmentId,
                    'designation_id' => $data->designationId,
                    'role_id' => $data->roleId,
                    'personal_email' => $data->personal_email,
                    'preferred_email' => $data->preferred_email,
                    'joining_date' => $data->formattedJoiningDate,
                    'career_start_date' => $formattedCareerStartDate,
                    'last_organization_id' => $create_org->id
                ]);
                DB::commit();
                return true;
            } else {
                DB::table('basic_info')->where('user_id', $this->id)->update([
                    'branch_id' => $data->branchId,
                    'department_id' => $data->departmentId,
                    'designation_id' => $data->designationId,
                    'role_id' => $data->roleId,
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
    public function getUserAddress($id)
    {
        return UserAddress::where('user_id',$id)->get();
    }
    public function getInstituteDegree($academy)
    {
        $result = [];
        if ($academy->count() > 0) {
            $academyArray = $academy->toArray();
            $instituteIds = array_column($academyArray, 'institute_id');
            $degreeIds = array_column($academyArray, 'degree_id');
            for ($i=0; $i<count($instituteIds); $i=$i+1)
            {
                $result[] = [
                    'institute_name' => Institute::where('id', $instituteIds[$i])->pluck('name')->first(),
                    'degree_name' => Degree::where('id', $degreeIds[$i])->pluck('name')->first(),
                ];
            }
        }
        return $result;
    }

    public function savePersonalInfo()
    {
        $personal_info = PersonalInfo::where('user_id',$this->id)->first();
        if(!$personal_info)
        {
            $personal_info = new PersonalInfo();
            $personal_info->user_id = $this->id;
            $personal_info->created_at = $this->created_at;
        }
        else
        {
            $personal_info->updated_at = $this->updated_at;
        }
        $personal_info->father_name = $this->father_name;
        $personal_info->mother_name	 = $this->mother_name	;
        $personal_info->nid = $this->nid;
        $personal_info->birth_certificate = $this->birth_certificate;
        $personal_info->passport_no = $this->passport_no;
        $personal_info->gender = $this->gender;
        $personal_info->religion = $this->religion;
        $personal_info->blood_group = $this->blood_group;
        $personal_info->dob = Carbon::createFromFormat('d-m-Y', $this->dob)->format('Y-m-d');
        $personal_info->marital_status = $this->marital_status;
        $personal_info->no_of_children = $this->no_of_children;
        return $personal_info->save();
    }
    public function saveUserAdress()
    {
        $date = date('Y-m-d H:i:s');
        DB::beginTransaction();
        try {
        $user_address = UserAddress::where('user_id',$this->id)->first();
        if(!$user_address)
        {
            $user_address = new UserAddress();
            $user_address->user_id = $this->id;
            $user_address->created_at = $this->created_at;
        }
        else
        {
            $user_address->updated_at = $this->updated_at;
        }
        $user_address->type= Config::get('variable_constants.address.present');
        $user_address->address= $this->present_address;
        $user_address->save();


        $user_address = UserAddress::where('user_id',$this->id)->skip(1)->first();
        if(!$user_address)
        {
            $user_address = new UserAddress();
            $user_address->user_id = $this->id;
            $user_address->created_at = $this->created_at;
        }
        else
        {
            $user_address->updated_at = $this->updated_at;
        }
        $user_address->type= Config::get('variable_constants.address.permanent');
        $user_address->address= $this->permanent_address;
        $user_address->save();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function saveEmergencyContact()
    {
        DB::beginTransaction();
        try {
        $emergency_contact = EmergencyContact::where('user_id',$this->id)->first();
        if(!$emergency_contact)
        {
            $emergency_contact = new EmergencyContact();
            $emergency_contact->user_id = $this->id;
            $emergency_contact->created_at = $this->created_at;
        }
        else
        {
            $emergency_contact->updated_at = $this->updated_at;
        }
        $emergency_contact->name  =$this->emergency_contact_name;
        $emergency_contact->relation = $this->relation;
        $emergency_contact->phone_number = $this->emergency_contact;
        $emergency_contact->save();

        $emergency_contact = EmergencyContact::where('user_id',$this->id)->skip(1)->first();
        if(!$emergency_contact)
        {
            $emergency_contact = new EmergencyContact();
            $emergency_contact->user_id = $this->id;
            $emergency_contact->created_at = $this->created_at;
        }
        else
        {
            $emergency_contact->updated_at = $this->updated_at;
        }
        $emergency_contact->name  =$this->emergency_contact_name2;
        $emergency_contact->relation = $this->relation2;
        $emergency_contact->phone_number = $this->emergency_contact2;
        $emergency_contact->save();
            DB::commit();
        return true;
            } catch (\Exception $exception) {
        DB::rollBack();
        return $exception->getMessage();
        }
    }
    public function saveAcademicInfo()
    {
        DB::beginTransaction();
        try {
        for($i=0; $i<sizeof($this->institute_id); $i=$i+1)
        {
            $academic_info = AcademicInfo::where('user_id',$this->id)->where('degree_id',$this->degree_id[$i])->first();
            if(!$academic_info)
            {
                $academic_info = new AcademicInfo();
                $academic_info->user_id = $this->id;
                $academic_info->created_at = $this->created_at;
            }
            else
            {
                $academic_info->updated_at = $this->updated_at;
            }
            $academic_info->institute_id = $this->institute_id[$i];
            $academic_info->degree_id = $this->degree_id[$i];
            $academic_info->major = $this->major[$i];
            $academic_info->gpa = $this->gpa[$i];
            $academic_info->passing_year = $this->year[$i];
            $academic_info->save();
        }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function saveBankInfo()
    {
        DB::beginTransaction();
        try {
        $bank_info = BankingInfo::where('user_id',$this->id)->first();
        if(!$bank_info)
        {
            $bank_info = new BankingInfo();
            $bank_info->user_id = $this->id;
            $bank_info->created_at = $this->created_at;
        }
        else
        {
            $bank_info->updated_at = $this->updated_at;
        }
        $bank_info->bank_id =$this->bank_id;
        $bank_info->account_name =$this->account_name;
        $bank_info->account_number =$this->account_number;
        $bank_info->branch =$this->branch;
        $bank_info->routing_no =$this->routing_number;
        $bank_info->save();

        $nominee = Nominee::where('banking_info_id',$bank_info->id)->first();
        if(!$nominee)
        {
            $nominee = new Nominee();
            $nominee->banking_info_id = $bank_info->id;
            $nominee->created_at = $this->created_at;
        }
        else
        {
            $nominee->updated_at = $this->updated_at;
        }
        $nominee->name = $this->nominee_name;
        $nominee->nid = $this->nominee_nid;
        $nominee->photo = $this->file_name? $this->file_name:($nominee->photo? $nominee->photo:'');
        $nominee->relation = $this->nominee_relation;
        $nominee->phone_number = $this->nominee_phone_number;
        $nominee->email = $this->nominee_email;
        $nominee->save();
            DB::commit();
        return true;
            } catch (\Exception $exception) {
        DB::rollBack();
        return $exception->getMessage();
        }
    }
    public function registraionComplete()
    {
        $user = User::where('id',$this->id)->first();
        $user->is_registration_complete = 1;
        return $user->save();
    }
    public function saveAllProfileInfo()
    {
        try{
            DB::beginTransaction();
            $this->savePersonalInfo();
            $this->saveUserAdress();
            $this->saveEmergencyContact();
            $this->saveAcademicInfo();
            $this->saveBankInfo();
            $this->registraionComplete();
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function getOfficialInfo($id)
    {
        return DB::table('basic_info as basic')->where('user_id',$id)
            ->join('branches as b', 'basic.branch_id','=', 'b.id')
            ->join('designations as d', 'basic.designation_id','=', 'd.id')
            ->join('departments as dep', 'basic.department_id','=', 'dep.id')
            ->join('roles as r', 'basic.role_id','=', 'r.id')
            ->select('b.name as branch_name', 'd.name as designation_name', 'dep.name as department_name', 'r.name as role_name')
            ->first();
    }
    public function isSuperUser($id)
    {
        $user = User::where('id',$id)->select('is_super_user')->first();
        return $user->is_super_user;
    }
    public function getAllUsers($id)
    {
        return User::where('id','!=',$id)->get();
    }
    public function getLineManagers($id)
    {
        return LineManager::where('user_id',$id)->get();
    }
}
