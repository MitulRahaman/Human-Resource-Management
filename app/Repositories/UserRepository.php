<?php

namespace App\Repositories;

use App\Models\AcademicInfo;
use App\Models\Asset;
use App\Models\Bank;
use App\Models\BankingInfo;
use App\Models\Degree;
use App\Models\EmergencyContact;
use App\Models\Institute;
use App\Models\LineManager;
use App\Models\Nominee;
use App\Models\PersonalInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $account_number, $branch, $routing_number, $nominee_email, $nominee_phone_number, $nominee_relation, $nominee_nid, $nominee_name, $assets, $offset, $limit;

    public function setOffset($offset)
    {
        $this->offset= $offset;
        return $this;
    }
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
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
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;
        return $this;
    }
    public function setPersonalEmail($personalEmail)
    {
        $this->personalEmail = $personalEmail;
        return $this;
    }
    public function setPreferredEmail($preferredEmail)
    {
        $this->preferredEmail = $preferredEmail;
        return $this;
    }
    public function setLineManager($lineManager)
    {
        $this->lineManager = $lineManager;
        return $this;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;
        return $this;
    }
    public function setOrganizationName($organizationName)
    {
        $this->organizationName = $organizationName;
        return $this;
    }
    public function setJoiningDate($joiningDate)
    {
        $this->joiningDate = $joiningDate;
        return $this;
    }
    public function setCareerStartDate($careerStartDate)
    {
        $this->careerStartDate = $careerStartDate;
        return $this;
    }
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
    //end set basic  info

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setAssets($assets)
    {
        $this->assets = $assets;
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
        if($data['branchId'] != null) {
            $firstBranch = DB::table('branch_departments')->where('branch_id', '=', $data->branchId)->first();
            return DB::table('designations')->where('department_id', '=', $firstBranch->department_id)->get();
        } else {
            return DB::table('designations')->where('department_id', '=', $data->departmentId)->get();
        }
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

    public function storeUser()
    {
            $formattedJoiningDate = date("Y-m-d", strtotime($this->joiningDate));
            if ($this->careerStartDate == null) {
                $formattedCareerStartDate = $formattedJoiningDate;
            } else {
                $formattedCareerStartDate = date("Y-m-d", strtotime($this->careerStartDate));
            }

        try {
            DB::beginTransaction();

            $organization_id = null;
            if ($this->organizationId) {
                $organization_id = $this->organizationId;
            }
            else if ($this->organizationName) {
                $organization = new Organization();
                $organization->name = $this->organizationName;
                $organization->created_at = date('Y-m-d');
                $organization->save();
                $organization_id = $organization->id;
            }
            $create_user = User::create([
                'employee_id' => $this->employeeId,
                'full_name' => $this->fullName,
                'nick_name' => $this->nickName,
                'email' => $this->preferredEmail,
                'phone_number' => $this->phone,
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
                'branch_id' => $this->branchId,
                'department_id' => $this->departmentId,
                'designation_id' => $this->designationId,
                'role_id' => $this->roleId,
                'personal_email' => $this->personalEmail,
                'preferred_email' => $this->preferredEmail,
                'joining_date' => $formattedJoiningDate,
                'career_start_date' => $formattedCareerStartDate,
                'last_organization_id' => $organization_id
            ]);
            if($this->lineManager) {
                foreach ($this->lineManager as $line_manager) {
                    LineManager::create([
                        'user_id'=>$create_user->id,
                        'line_manager_user_id' => $line_manager,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function updateUser()
    {
        $formattedJoiningDate = date("Y-m-d", strtotime($this->joiningDate));
        if ($this->careerStartDate == null) {
            $formattedCareerStartDate = $formattedJoiningDate;
        } else {
            $formattedCareerStartDate = date("Y-m-d", strtotime($this->careerStartDate));
        }
        try {
            DB::beginTransaction();

            $user = User::find($this->id);
            if($this->file == null)
                $this->file = $user->image;

            DB::table('users')->where('id', $this->id)->update([
                'full_name' => $this->fullName,
                'nick_name' => $this->nickName,
                'email' => $this->preferredEmail,
                'phone_number' => $this->phone,
                'image' => $this->file,
                'is_super_user' => 0,
                'is_registration_complete' => 0,
                'is_password_changed' => 0,
                'is_onboarding_complete' => 0,
                'status' => 1
            ]);
            if($this->lineManager) {
                LineManager::where('user_id',$this->id)->delete();
                foreach ($this->lineManager as $line_manager) {
                    LineManager::create([
                        'user_id'=>$this->id,
                        'line_manager_user_id' => $line_manager,
                    ]);
                }
            } else {
                LineManager::where('user_id',$this->id)->delete();
            }

            if($this->organizationName!= null && !is_numeric($this->organizationName)) {
                $create_org = Organization::create([
                    'name' => $this->organizationName
                ]);
                DB::table('basic_info')->where('user_id', $this->id)->update([
                    'branch_id' => $this->branchId,
                    'department_id' => $this->departmentId,
                    'designation_id' => $this->designationId,
                    'role_id' => $this->roleId,
                    'personal_email' => $this->personalEmail,
                    'preferred_email' => $this->preferredEmail,
                    'joining_date' => $formattedJoiningDate,
                    'career_start_date' => $formattedCareerStartDate,
                    'last_organization_id' => $create_org->id
                ]);
                DB::commit();
                return true;
            } else {
                DB::table('basic_info')->where('user_id', $this->id)->update([
                    'branch_id' => $this->branchId,
                    'department_id' => $this->departmentId,
                    'designation_id' => $this->designationId,
                    'role_id' => $this->roleId,
                    'personal_email' => $this->personalEmail,
                    'preferred_email' => $this->preferredEmail,
                    'joining_date' => $formattedJoiningDate,
                    'career_start_date' => $formattedCareerStartDate,
                    'last_organization_id' => $this->organizationName
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
        $data->update(array('status' => Config::get('variable_constants.check.no')));
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
            return $data->update(array('status' => Config::get('variable_constants.check.no')));
        else
            return $data->update(array('status' => Config::get('variable_constants.check.yes')));
    }

    public function resetPassword($id)
    {
        $data = User::find($id);
        return $data->update(array('password' => Hash::make('welcome'), 'is_password_changed' => Config::get('variable_constants.check.no')));
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
    public function totalUserAssets()
    {
        return DB::table('user_assets')
            ->whereNull('deleted_at')
            ->where('user_id', '=', Auth::id())
            ->count();
    }
    public function getAssetsTaken()
    {
        return DB::table('user_assets as ua')
            ->whereNull('ua.deleted_at')
            ->where('ua.user_id', '=', $this->id)
            ->leftJoin('assets as a', 'a.id', '=', 'ua.asset_id')
            ->leftJoin('asset_images as ai', 'ai.asset_id', '=', 'ua.asset_id')
            ->leftJoin('asset_types as at', 'at.id', '=', 'a.type_id')
            ->select('a.name', 'a.specification', 'a.condition', 'ai.url as image', 'at.name as type', 'ua.status', 'ua.id', 'ua.created_at', DB::raw('(CASE WHEN ua.requisition_request_id IS NULL THEN "no" ELSE "yes" END) as by_requisition'))
            ->offset($this->offset)
            ->limit($this->limit)
            ->orderBy('ua.id','desc')
            ->get();
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
        if($id == null) {
            return User::where('is_super_user', '=', Config::get('variable_constants.check.no'))->get();
        } else {
            return User::where('id','!=',$id)->where('is_super_user', '=', Config::get('variable_constants.check.no'))->get();
        }
    }
    public function getLineManagers($id)
    {
        return LineManager::where('user_id',$id)->get();
    }
    public function getAvailableLeave($id)
    {
        $currentYear = now()->year;
        $leaves = DB::table('total_yearly_leaves')->where('year', '=', $currentYear)->pluck('total_leaves')->toArray();
        $total_yearly_leaves = array_sum($leaves);
        $used_leaves = DB::table('leaves')->where('user_id', '=' ,$id)->where('status', '=',Config::get('variable_constants.leave_status.approved'))->pluck('total')->toArray();
        $total_used = array_sum($used_leaves);
        return $total_yearly_leaves-$total_used;
    }
    public function getAllAssets()
    {
        $branch_id = DB::table('basic_info')->where('user_id','=', $this->id)->select('branch_id')->first();
        return DB::table('assets as a')
            ->whereNull('a.deleted_at')
            ->where('a.status', '=',Config::get('variable_constants.activation.active'))
            ->where('a.condition', '=',Config::get('variable_constants.asset_condition.good'))
            ->where('a.branch_id','=', $branch_id->branch_id)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('user_assets as ua')
                    ->where('ua.status', '=', Config::get('variable_constants.activation.active'))
                    ->whereRaw('ua.asset_id = a.id');
            })
            ->get();
    }
    public function updateDistributeAsset()
    {
        DB::beginTransaction();
        try {
            $branch_id = DB::table('basic_info')->where('user_id','=', $this->id)->select('branch_id')->first();
            $asset =[];
            foreach ($this->assets as $asset_id)
            {
                $asset[]=DB::table('user_assets')
                            ->insertGetId([
                                'user_id' => $this->id,
                                'asset_id' => $asset_id,
                                'branch_id' => $branch_id->branch_id,
                                'status' => Config::get('variable_constants.activation.active'),
                                'created_at' => $this->created_at
                            ]);
            }
            if($asset)
            {
                $user= DB::table('users')->where('id','=',$this->id)->first();
                if($user->is_asset_distributed==Config::get('variable_constants.check.no'))
                {
                     DB::table('users')->where('id','=',$this->id)
                                ->update(['is_asset_distributed'=>Config::get('variable_constants.check.yes')]);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function isAssetDistributed($id)
    {
        $user = User::where('id','=',$id)->select('is_asset_distributed')->first();
        return $user->is_asset_distributed;
    }
    public function requisitionRequest($id)
    {
        $status = DB::table('requisition_requests')->where('user_id', $id)
            ->where('status', '=',Config::get('variable_constants.status.approved'))
            ->first();
        return $status;
    }
}
